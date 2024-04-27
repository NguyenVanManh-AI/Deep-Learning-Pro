<?php

namespace App\Console\Commands;

use App\Enums\ChannelEnum;
use App\Models\Broadcast;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentBroadcast;
use App\Models\User;
use App\Traits\APIResponse;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SendBroadCastEveryMinuteCommand extends Command
{
    use APIResponse;

    protected $signature = 'user:broadcast';

    protected $description = 'This command will run once but repeat every minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        dump('-------------------------------- Send broadcast every minute - ' . now()->format('Y-m-d H:i:s') . ' -----------------------------------');
        info('-------------------------------- Send broadcast every minute - ' . now()->format('Y-m-d H:i:s') . ' -----------------------------------');

        // lấy ra broadcast hiện tại
        $currentDateTime = now();
        $minuteStart = $currentDateTime->format('Y-m-d H:i:00');
        $minuteEnd = $currentDateTime->format('Y-m-d H:i:59');
        $broadcasts = Broadcast::whereBetween('sent_at', [$minuteStart, $minuteEnd])
            ->where('status', 'scheduled')->where('is_delete', 0)->get();

        foreach ($broadcasts as $broadcast) {
            dump('Broadcast title : ' . $broadcast->title);
            info('Broadcast title : ' . $broadcast->title);
            DB::beginTransaction();
            try {
                // sending
                $user = User::find($broadcast->sender_id);
                $channelAccessToken = Channel::find($user->channel_id)->channel_access_token;
                $client = new Client([
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $channelAccessToken,
                    ],
                ]);

                $content_broadcasts = ContentBroadcast::where('broadcast_id', $broadcast->id)
                    ->orderBy('sequence', 'ASC')->get();

                $messages = [];
                foreach ($content_broadcasts as $key => $item) {
                    if($item->content_id) {
                        $content = Content::find($item->content_id);
                        $messages[] = json_decode($content->content_data);
                    }
                    else {
                        $content = (object) json_decode($item->content_sticker)->content_data;
                        $content->type = 'sticker';
                        $messages[] = $content;
                    }
                }
                $data = ['messages' => $messages];
                $response = $client->post(ChannelEnum::PATH_BROADCAST, ['json' => $data]);
                // sending

                // save to database
                // status : sent
                if ($response->getStatusCode() == 200) {
                    $broadcast->status = 'sent';
                    $broadcast->save();
                    dump('    The message has been sent successfully !');
                    info('    The message has been sent successfully !');
                }
                // status : failed
                else {
                    $broadcast->status = 'failed';
                    $broadcast->save();
                    dump('    The message has been sent successfully !');
                    info('    The message has been sent successfully !');
                }
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
            }
        }
        // Đợi 60 giây (1 phút)
        sleep(10);
        // sleep(60);
        // Gọi lại hàm handle() để lặp lại công việc
        $this->handle();
    }
}
