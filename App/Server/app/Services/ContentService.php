<?php

namespace App\Services;

use App\Http\Requests\RequestAddContent;
use App\Http\Requests\RequestChangeIsDeleteContent;
use App\Http\Requests\RequestChangeIsDeleteManyContent;
use App\Http\Requests\RequestUpdateContent;
use App\Models\Content;
use App\Models\User;
use App\Repositories\ContentInterface;
use App\Repositories\ContentRepository;
use App\Traits\APIResponse;
use App\Traits\InformationChannel;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContentService
{
    use APIResponse;
    use InformationChannel;

    protected ContentInterface $contentRepository;

    public function __construct(
        ContentInterface $contentRepository,
    ) {
        $this->contentRepository = $contentRepository;
    }

    public function addContent(RequestAddContent $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $data = [
                'user_id' => $user->id,
                'is_delete' => 0,
                'create_id' => $user->id,
                'update_id' => $user->id,
            ];

            $contentType = $request->content_type;
            switch ($contentType) {
                case 'text':
                case 'sticker':
                    $data['content_type'] = $contentType;
                    $data['content_data'] = json_encode($request->content_data, JSON_UNESCAPED_UNICODE);
                    break;

                case 'image':
                    // upload file // chỗ này request bắt buộc có ảnh rồi nên không if nữa
                    $image_content = $request->file('image_content');
                    $uploadedFile = Cloudinary::upload($image_content->getRealPath(), ['folder' => 'contents', 'resource_type' => 'auto']);
                    $imgUrl = $uploadedFile->getSecurePath();
                    // upload profile
                    $data['content_type'] = $contentType;
                    $data['content_data'] = json_encode([
                        'type' => 'image',
                        'originalContentUrl' => $imgUrl,
                        'previewImageUrl' => $imgUrl,
                    ], JSON_UNESCAPED_UNICODE);
                    break;
            }

            $newContent = Content::create($data);
            $newContent->content_data = json_decode($newContent->content_data);

            DB::commit();

            return $this->responseSuccessWithData($newContent, 'Add new content successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function updateContent(RequestUpdateContent $request, $id_content)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $oldContent = Content::find($id_content);
            $backupOldContent = Content::find($id_content);
            if (empty($oldContent)) {
                return $this->responseError('Not Found Content !', 404);
            }

            switch ($user->role) {
                case 'user':
                    if ($oldContent->user_id != $user->id) {
                        return $this->responseError('You have no rights !', 400);
                    }
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    $idContentChannels = Content::whereIn('user_id', $idUserChannels)->pluck('id')->toArray();
                    if (!in_array($id_content, $idContentChannels)) {
                        return $this->responseError('You have no rights !', 400);
                    }
                    break;
            }

            $data = [
                'update_id' => $user->id,
            ];

            $contentType = $request->content_type;
            switch ($contentType) {
                case 'text':
                case 'sticker':
                    $data['content_type'] = $contentType;
                    $data['content_data'] = json_encode($request->content_data, JSON_UNESCAPED_UNICODE);
                    break;

                case 'image':
                    // upload file // chỗ này request bắt buộc có ảnh rồi nên không if nữa
                    $image_content = $request->file('image_content');
                    $uploadedFile = Cloudinary::upload($image_content->getRealPath(), ['folder' => 'contents', 'resource_type' => 'auto']);
                    $imgUrl = $uploadedFile->getSecurePath();
                    // upload profile
                    $data['content_type'] = $contentType;
                    $data['content_data'] = json_encode([
                        'type' => 'image',
                        'originalContentUrl' => $imgUrl,
                        'previewImageUrl' => $imgUrl,
                    ], JSON_UNESCAPED_UNICODE);
                    break;
            }

            $oldContent->update($data);
            $oldContent->content_data = json_decode($oldContent->content_data);

            // 7 là tính từ sau https://res.cloudinary.com/dzve8benj/image/upload/v1708073923/ chắc chắn sẽ đến id dù có vào bao nhiêu folder đi nữa
            // => folder tên gì , mấy kí tự dùng cũng được
            // delete old file
            if ($backupOldContent->content_type == 'image') {
                $contentData = json_decode($backupOldContent->content_data);
                $id_file = explode('.', implode('/', array_slice(explode('/', $contentData->originalContentUrl), 7)))[0];
                Cloudinary::destroy($id_file);
            }
            // delete old file

            DB::commit();

            return $this->responseSuccessWithData($oldContent, 'Update content successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function contentDetail(Request $request, $id_content)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $content = Content::find($id_content);
            if (empty($content)) {
                return $this->responseError('Not Found Content !', 404);
            }
            $content->content_data = json_decode($content->content_data);

            DB::commit();

            return $this->responseSuccessWithData($content, 'Get content detail successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsDeleteContent(RequestChangeIsDeleteContent $request, $id_content)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $content = Content::find($id_content);
            if (empty($content)) {
                return $this->responseError('Not Found Content !', 404);
            }

            switch ($user->role) {
                case 'user':
                    if ($content->user_id != $user->id) {
                        return $this->responseError('You have no rights !');
                    }
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    $idContentChannels = Content::whereIn('user_id', $idUserChannels)->pluck('id')->toArray();
                    if (!in_array($id_content, $idContentChannels)) {
                        return $this->responseError('You have no rights !');
                    }
                    break;
            }

            $content->update(['is_delete' => $request->is_delete]);

            DB::commit();
            $content->content_data = json_decode($content->content_data);

            return $this->responseSuccessWithData($content, 'Change is delete content successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsDeleteManyContent(RequestChangeIsDeleteManyContent $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);

            switch ($user->role) {
                case 'user':
                    Content::whereIn('id', $request->ids_content)->where('user_id', $user->id)->update(['is_delete' => $request->is_delete]);
                    DB::commit();
                    break;

                case 'manager':
                    $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();
                    Content::whereIn('id', $request->ids_content)->whereIn('user_id', $idUserChannels)->update(['is_delete' => $request->is_delete]);
                    DB::commit();
                    break;
            }

            return $this->responseSuccess('Change is delete many content successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function getContents(Request $request)
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

                case 'content_type':
                    $orderBy = 'content_type';
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
                'content_type' => $request->content_type ?? 'all',
                'is_delete' => $request->is_delete ?? 'all',
                'idUserChannels' => $idUserChannels,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            $contents = ContentRepository::getContents($filter);
            if (!(empty($request->paginate))) {
                $contents = $contents->paginate($request->paginate);
            } else {
                $contents = $contents->get();
            }

            foreach ($contents as $content) {
                $content->content_data = json_decode($content->content_data);
            }

            return $this->responseSuccessWithData($contents, 'Get contents successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function getContentForBroadcast(Request $request)
    {
        try {
            $user = User::find(auth('user_api')->user()->id);
            $idUserChannels = $this->getIdUserChannel();

            $datas = (object) [];
            // sticker
            $filter = (object) [
                'is_delete' => '0',
                'idUserChannels' => $idUserChannels,
            ];

            // image
            $filter->content_type = 'image';
            $contentImages = ContentRepository::getContents($filter)->get();
            $datas->images = $contentImages;

            // text
            $filter->search = $request->search ?? '';
            $filter->content_type = 'text';
            $contentTexts = ContentRepository::getContents($filter)->get();
            $datas->texts = $contentTexts;

            foreach ($datas as $data) {
                foreach ($data as $value) {
                    $value->content_data = json_decode($value->content_data);
                }
            }

            return $this->responseSuccessWithData($datas, 'Get contents of channel for broadcast successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }
}
