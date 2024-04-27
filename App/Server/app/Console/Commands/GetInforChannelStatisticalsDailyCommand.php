<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\ChannelStatistical;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class GetInforChannelStatisticalsDailyCommand extends Command
{
    protected $signature = 'user:statistical';

    protected $description = 'This command will run once but repeat every minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        dump('-------------------------------- Get information channel every day - ' . now()->subDay()->format('Y-m-d H:i:s') . ' -----------------------------------');
        $dateYesterday = now()->subDay()->format('Ymd');
        $collectionDate = now()->subDay()->format('Y-m-d');

        $urls = [
            // 'info' => 'https://api.line.me/v2/bot/info',
            'message_delivery' => 'https://api.line.me/v2/bot/insight/message/delivery?date=' . $dateYesterday,
            'followers' => 'https://api.line.me/v2/bot/insight/followers?date=' . $dateYesterday
        ];

        try {
            $channels = Channel::whereNotNull('channel_access_token')->get();
            foreach ($channels as $channel) {
                dump('Channel Name : ' . $channel->channel_name);
                DB::beginTransaction();
                try { // phải bỏ beginTransaction trong đây , channel nào thì commit riêng channel đấy 
                    $channel_statistical = ChannelStatistical::where('channel_id', $channel->id)->where('date', $collectionDate)->first();
                    if ($channel_statistical) {
                        dump('    + Collected . Continue...');
                        continue;
                    } else {
                        $data = [
                            'channel_id' => $channel->id,
                            'date' => $collectionDate,
                            'api_broadcast' => null,
                            'api_multicast' => null,
                            'followers' => null,
                            'targeted_reaches' => null,
                            'blocks' => null,
                        ];
                        $channelAccessToken = $channel->channel_access_token;
                        foreach ($urls as $key => $url) {
                            $client = new Client([
                                'headers' => [
                                    'Content-Type' => 'application/json',
                                    'Authorization' => 'Bearer ' . $channelAccessToken,
                                ],
                            ]);

                            $response = $client->get($url);
                            $statusCode = $response->getStatusCode();
                            $body = $response->getBody()->getContents();

                            if ($statusCode == 200) {
                                $bodyArray = json_decode($body, true);
                                if ($key == 'message_delivery') {
                                    $data['api_broadcast'] = $bodyArray['apiBroadcast'] ?? 0;
                                    $data['api_multicast'] = $bodyArray['apiMulticast'] ?? 0;
                                    dump('    + Get daily information message delivery continuously successfully !');
                                }
                                if ($key == 'followers') {
                                    $data['followers'] = $bodyArray['followers'] ?? 0;
                                    $data['targeted_reaches'] = $bodyArray['targetedReaches'] ?? 0;
                                    $data['blocks'] = $bodyArray['blocks'] ?? 0;
                                    dump('    + Get daily information followers continuously successfully !');
                                }
                                // dump($body);
                            } else {
                                dump('    + Getting daily information failed. Status code: ' . $statusCode);
                                // dump($body);
                            }

                        }
                        $statis_channel = ChannelStatistical::create($data);
                        dump('    + Save follower count and message count information every day successfully : ');
                        dump('        ' . $statis_channel);
                    }
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollback();
                    dump('Error sending request:' . $e->getMessage());
                }
            }
        } catch (Throwable $e) {
            dump('' . $e->getMessage());
        }
        sleep(10);
        // sleep(86400);
        $this->handle();

    }
}

/*
    // NOTE : Check xem ngày đó có số liệu chưa mới lưu vào , lỡ call nhiều lần lưu nhiều lần
    // lưu cho nhiều channel 
    dump('-------------------------------- Get information channel every day - ' . now()->format('Y-m-d H:i:s') . ' -----------------------------------');
    // vì số liệu mỗi xong mỗi ngày mới được tổng kết nên lấy số liệu chậm một ngày
    // ở đây là lấy số liệu của ngày hôm qua

    /*
        $url = 'https://api.line.me/v2/bot/info' ; // Lấy thông tin của channel và cập nhật lại vào database 
            {
                "userId": "U5551e99910d2703631e9a9a39c071dd2",
                "basicId": "@507frvih",
                "displayName": "DemoPHP2",
                "pictureUrl": "https://profile.line-scdn.net/0hCZKsFdDrHHV2Cg_BePxjIkpPEhgBJBo9DmVaE1ZdS0JTaVIqS28AFwYDFkYJalJ3Gj9RRwNYRU1e",
                "chatMode": "chat",
                "markAsReadMode": "manual"
            }

        $url = 'https://api.line.me/v2/bot/insight/message/delivery?date=' . $dateYesterday; // thống kê số message mỗi ngày 
            {
                "status" : "ready",
                "apiBroadcast" : 6, // gửi bằng broadcast
                "apiMulticast" : 3 // gửi bằng multicast
            }

        $url = 'https://api.line.me/v2/bot/insight/followers?date=' . $dateYesterday; // thống kê số follow 
            {
                "status" : "ready",
                "followers" : 2,
                "targetedReaches" : 2,
                "blocks" : 0
            }
    */

// Get number of message deliveries // Số lần gửi tin nhắn
// https://developers.line.biz/en/reference/messaging-api/#get-number-of-delivery-messages
/*
{
    "status" : "ready",
    "apiBroadcast" : 6, // gửi bằng broadcast
    "apiMulticast" : 3 // gửi bằng multicast
}

*/
