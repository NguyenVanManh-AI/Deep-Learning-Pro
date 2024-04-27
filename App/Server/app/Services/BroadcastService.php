<?php

namespace App\Services;

use App\Enums\ChannelEnum;
use App\Http\Requests\RequestAddBroadcast;
use App\Http\Requests\RequestChangeIsDeleteBroadcast;
use App\Http\Requests\RequestChangeIsDeleteManyBroadcast;
use App\Http\Requests\RequestSendNow;
use App\Http\Requests\RequestTestSend;
use App\Http\Requests\RequestUpdateBroadcast;
use App\Models\Broadcast;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentBroadcast;
use App\Models\User;
use App\Repositories\BroadcastInterface;
use App\Repositories\BroadcastRepository;
use App\Traits\APIResponse;
use App\Traits\InformationChannel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BroadcastService
{
    use APIResponse;
    use InformationChannel;

    protected BroadcastInterface $broadcastRepository;

    public function __construct(
        BroadcastInterface $broadcastRepository,
    ) {
        $this->broadcastRepository = $broadcastRepository;
    }

    public function addBroadcast(RequestAddBroadcast $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);

            // check for non-channle content
            $idContentChannels = $this->getIdContentChannelNoDelete();
            $content_ids = $request->content_ids;
            $errors = [];
            foreach ($content_ids as $key => $value) {
                if(is_numeric($value)) {
                    if (!in_array($value, $idContentChannels)) {
                        $errors[] = $key;
                    }
                }
            }
            if (!empty($errors)) {
                $messageErrors = [];
                foreach ($errors as $error) {
                    $n = $error + 1;
                    $messageErrors[] = "Content in position $n not part of the channels content or has been deleted !";
                }

                return $this->responseError($messageErrors);
            }
            // check for non-channle content

            // create broadcast
            $dataBroadcast = [
                'line_user_id' => $user->line_user_id,
                'title' => $request->title,
                'sent_at' => $request->sent_at,
                'is_delete' => 0,
                'sender_id' => $user->id,
                'status' => $request->status,
            ];
            $newBroadcast = Broadcast::create($dataBroadcast);

            // create content_broadcast
            foreach ($content_ids as $index => $id_or_sticker) {
                $dataContentBroadcast = [
                    'user_id' => $user->id,
                    'broadcast_id' => $newBroadcast->id,
                    'content_id' => is_numeric($id_or_sticker) ? $id_or_sticker : null ,
                    'content_sticker' => null,
                    'sequence' => $index,
                ];

                if(is_array($id_or_sticker)) {
                    $content_sticker = (object) [
                        'content_type' => 'sticker',
                        'content_data' => $id_or_sticker,
                    ];
                    $dataContentBroadcast['content_sticker'] = json_encode($content_sticker);
                }

                ContentBroadcast::create($dataContentBroadcast);
            }

            DB::commit();

            return $this->responseSuccessWithData($newBroadcast, 'Broadcast added successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function sendNow(RequestSendNow $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $idContentChannels = $this->getIdContentChannelNoDelete();
            $content_ids = $request->content_ids;

            // check for non-channle content
            $errors = [];
            foreach ($content_ids as $key => $value) {
                if (!in_array($value, $idContentChannels)) {
                    $errors[] = $key;
                }
            }
            if (!empty($errors)) {
                $messageErrors = [];
                foreach ($errors as $error) {
                    $n = $error + 1;
                    $messageErrors[] = "Content in position $n not part of the channels content or has been deleted !";
                }

                return $this->responseError($messageErrors);
            }
            // check for non-channle content

            // sending
            $channelAccessToken = Channel::find($user->channel_id)->channel_access_token;
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $channelAccessToken,
                ],
            ]);

            $messages = [];
            foreach ($content_ids as $key => $content_id) {
                $content = Content::find($content_id);
                $messages[] = json_decode($content->content_data);
            }
            $data = ['messages' => $messages];
            $response = $client->post(ChannelEnum::PATH_BROADCAST, ['json' => $data]);
            // sending

            // create broadcast
            $dataBroadcast = [
                'line_user_id' => $user->line_user_id,
                'title' => $request->title,
                'sent_at' => now(),
                'is_delete' => 0,
                'sender_id' => $user->id,
                'status' => 'scheduled',
            ];
            $newBroadcast = Broadcast::create($dataBroadcast);

            // create content_broadcast
            foreach ($content_ids as $index => $content_id) {
                $dataContentBroadcast = [
                    'user_id' => $user->id,
                    'broadcast_id' => $newBroadcast->id,
                    'content_id' => $content_id,
                    'sequence' => $index,
                ];
                ContentBroadcast::create($dataContentBroadcast);
            }

            // save to database
            // status : sent
            if ($response->getStatusCode() == 200) {
                $newBroadcast->update(['status' => 'sent']);
                $messageReturn = 'The message has been sent successfully !';
            }
            // status : failed
            else {
                $newBroadcast->update(['status' => 'failed']);
                $messageReturn = 'Sending message failed !';
            }

            DB::commit();

            return $this->responseSuccessWithData($newBroadcast, $messageReturn);
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function getBroadcasts(Request $request)
    {
        try {
            $user = User::find(auth('user_api')->user()->id);
            $idUserChannels = [];

            if ($user->role == 'user') {
                $idUserChannels[] = $user->id;
            } else {
                if ($request->role == 'manager') {
                    $idUserChannels[] = $user->id;
                } else {
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                }
            }

            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'users.name';
                    break;

                case 'status':
                    $orderBy = 'status';
                    break;

                case 'title':
                    $orderBy = 'title';
                    break;

                case 'new':
                    $orderBy = 'id';
                    break;

                default:
                    $orderBy = 'id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'status' => $request->status ?? 'all',
                'is_delete' => $request->is_delete ?? 'all',
                'idUserChannels' => $idUserChannels,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            $broadcasts = BroadcastRepository::getBroadcasts($filter);
            if (!(empty($request->paginate))) {
                $broadcasts = $broadcasts->paginate($request->paginate);
            } else {
                $broadcasts = $broadcasts->get();
            }

            foreach ($broadcasts as $broadcast) {
                $content_broadcasts = ContentBroadcast::where('broadcast_id', $broadcast->id)->get();
                $content_ids = [];
                $contents = [];
                foreach ($content_broadcasts as $item) {
                    if($item->content_id) {
                        $content = Content::find($item->content_id);
                        $content->content_data = json_decode($content->content_data);
                        $contents [] = $content;
                        $content_ids[] = $item->content_id;
                    }
                    else {
                        $id = json_decode($item->content_sticker)->content_data;
                        $data = (object) json_decode($item->content_sticker);
                        $data->id = $id;
                        $contents [] = $data;
                        $content_ids[] = json_decode($item->content_sticker)->content_data;
                    }

                }
                $broadcast->content_ids = $content_ids;
                $broadcast->contents = $contents;
            }

            return $this->responseSuccessWithData($broadcasts, 'Get broadcasts successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function getDetailBroadcast(Request $request, $id_broadcast)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $broadcast = Broadcast::find($id_broadcast);
            if (empty($broadcast)) {
                return $this->responseError('Not Found Broadcast !', 404);
            }
            
            switch ($user->role) {
                case 'user':
                    if ($broadcast->sender_id != $user->id) {
                        return $this->responseError('You have no rights !');
                    }
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    $idBroadcastChannels = Broadcast::whereIn('sender_id', $idUserChannels)->pluck('id')->toArray();
                    if (!in_array($id_broadcast, $idBroadcastChannels)) {
                        return $this->responseError('You have no rights !');
                    }
                    break;
            }

            $content_ids = ContentBroadcast::where('broadcast_id', $id_broadcast)->pluck('content_id')->toArray();
            $contents = [];
            foreach ($content_ids as $content_id) {
                $content = Content::find($content_id);
                $content->content_data = json_decode($content->content_data);
                $contents [] = $content;
            }
            $broadcast->content_ids = $content_ids;
            $broadcast->contents = $contents;

            DB::commit();

            return $this->responseSuccessWithData($broadcast, 'Get detail broadcast successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function updateBroadcast(RequestUpdateBroadcast $request, $id_broadcast)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $broadcast = Broadcast::find($id_broadcast);
            if (empty($broadcast)) {
                return $this->responseError('Not Found Broadcast !', 404);
            }
            
            switch ($user->role) {
                case 'user':
                    if ($broadcast->sender_id != $user->id) {
                        return $this->responseError('You have no rights !');
                    }
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    $idBroadcastChannels = Broadcast::whereIn('sender_id', $idUserChannels)->pluck('id')->toArray();
                    if (!in_array($id_broadcast, $idBroadcastChannels)) {
                        return $this->responseError('You have no rights !');
                    }
                    break;
            }

            // check for non-channle content
            $idContentChannels = $this->getIdContentChannelNoDelete();
            $content_ids = $request->content_ids;
            $errors = [];
            foreach ($content_ids as $key => $value) {
                if(is_numeric($value)) {
                    if (!in_array($value, $idContentChannels)) {
                        $errors[] = $key;
                    }
                }
            }
            if (!empty($errors)) {
                $messageErrors = [];
                foreach ($errors as $error) {
                    $n = $error + 1;
                    $messageErrors[] = "Content in position $n not part of the channels content or has been deleted !";
                }

                return $this->responseError($messageErrors);
            }
            // check for non-channle content

            // create broadcast
            $dataBroadcast = [
                'title' => $request->title,
                'sent_at' => $request->sent_at,
                'status' => $request->status,
            ];
            $broadcast->update($dataBroadcast);

            ContentBroadcast::where('broadcast_id', $broadcast->id)->delete();

            // xóa hết content_broadcast của broadcast rồi tạo lại cho khỏe  
            // create content_broadcast
            foreach ($content_ids as $index => $id_or_sticker) {
                $dataContentBroadcast = [
                    'user_id' => $user->id,
                    'broadcast_id' => $broadcast->id,
                    'content_id' => is_numeric($id_or_sticker) ? $id_or_sticker : null ,
                    'content_sticker' => null,
                    'sequence' => $index,
                ];

                if(is_array($id_or_sticker)) {
                    $content_sticker = (object) [
                        'content_type' => 'sticker',
                        'content_data' => $id_or_sticker,
                    ];
                    $dataContentBroadcast['content_sticker'] = json_encode($content_sticker);
                }

                ContentBroadcast::create($dataContentBroadcast);
            }

            DB::commit();

            return $this->responseSuccessWithData($broadcast, 'Update broadcast successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }
    
    public function testSend(RequestTestSend $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $channelAccessToken = Channel::find($user->channel_id)->channel_access_token;

            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $channelAccessToken,
                ],
            ]);

            // check for non-channle content
            $idContentChannels = $this->getIdContentChannelNoDelete();
            $content_ids = $request->content_ids;
            $errors = [];
            foreach ($content_ids as $key => $value) {
                if(is_numeric($value)) {
                    if (!in_array($value, $idContentChannels)) {
                        $errors[] = $key;
                    }
                }
            }
            if (!empty($errors)) {
                $messageErrors = [];
                foreach ($errors as $error) {
                    $n = $error + 1;
                    $messageErrors[] = "Content in position $n not part of the channels content or has been deleted !";
                }

                return $this->responseError($messageErrors);
            }
            // check for non-channle content

            // check for non-channle member
            $idUserChannels = $this->getIdUserChannelGood();
            $member_ids = $request->member_ids;
            $errors = [];
            foreach ($member_ids as $key => $value) {
                if (!in_array($value, $idUserChannels)) {
                    $errors[] = $key;
                }
            }
            if (!empty($errors)) {
                $messageErrors = [];
                foreach ($errors as $error) {
                    $n = $error + 1;
                    $messageErrors[] = "Member in position $n not part of the channels or or the member has been deleted or locked !";
                }

                return $this->responseError($messageErrors);
            }
            // check for non-channle member

            $lineUserIds = User::whereIn('id', $member_ids)->pluck('line_user_id')->toArray();

            $messages = [];
            foreach ($content_ids as $key => $id_or_sticker) {
                if(is_numeric($id_or_sticker)) {
                    $content = Content::find($id_or_sticker);
                    $messages[] = json_decode($content->content_data);
                }
                else {
                    $id_or_sticker['type'] = 'sticker';
                    $content = $id_or_sticker;
                    $messages[] = $content;
                }
            }

            $data['to'] = $lineUserIds; // line user id không được fake , vì sẽ gửi không được
            $data['messages'] = $messages;
            $response = $client->post(ChannelEnum::PATH_MULTICAST, ['json' => $data]);

            if ($response->getStatusCode() == 200) {
                $messageReturn = 'The message has been sent successfully !';
            } else {
                $messageReturn = 'Sending message failed !';
            }

            DB::commit();

            return $this->responseSuccess($messageReturn);
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsDeleteBroadcast(RequestChangeIsDeleteBroadcast $request, $id_broadcast)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $broadcast = Broadcast::find($id_broadcast);
            if (empty($broadcast)) {
                return $this->responseError('Not Found Broadcast !', 404);
            }

            switch ($user->role) {
                case 'user':
                    if ($broadcast->sender_id != $user->id) {
                        return $this->responseError('You have no rights !');
                    }
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    $idBroadcastChannels = Broadcast::whereIn('sender_id', $idUserChannels)->pluck('id')->toArray();
                    if (!in_array($id_broadcast, $idBroadcastChannels)) {
                        return $this->responseError('You have no rights !');
                    }
                    break;
            }

            $broadcast->update(['is_delete' => $request->is_delete]);

            DB::commit();

            return $this->responseSuccessWithData($broadcast, 'Change is delete broadcast successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsDeleteManyBroadcast(RequestChangeIsDeleteManyBroadcast $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);

            switch ($user->role) {
                case 'user':
                    Broadcast::whereIn('id', $request->ids_broadcast)->where('sender_id', $user->id)->update(['is_delete' => $request->is_delete]);
                    DB::commit();
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    Broadcast::whereIn('id', $request->ids_broadcast)->whereIn('sender_id', $idUserChannels)->update(['is_delete' => $request->is_delete]);
                    DB::commit();
                    break;
            }

            return $this->responseSuccess('Change is delete many broadcast successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }
    
}
