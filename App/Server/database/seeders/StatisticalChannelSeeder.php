<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\ChannelStatistical;
use Carbon\Carbon;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class StatisticalChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDate = Carbon::parse($startDate = Carbon::now()->subMonth()); // 1 tháng trước 
        $endDate = Carbon::parse(Carbon::now()->format('Y-m-d')); // hiện tại 
        $channels = Channel::whereNotNull('channel_access_token')->get();
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            foreach ($channels as $channel) {
                DB::beginTransaction();
                try { 
                    $channel_statistical = ChannelStatistical::where('channel_id', $channel->id)->where('date', $date->toDateString())->first();
                    if ($channel_statistical) {
                        continue; // data có ngày này rồi thì bỏ qua 
                    } else {
                        $data = [
                            'channel_id' => $channel->id,
                            'date' => $date,
                            'api_broadcast' => rand(0, 100),
                            'api_multicast' => rand(0, 100),
                            'followers' => rand(0, 30),
                            'targeted_reaches' => rand(0, 30),
                            'blocks' => rand(0, 30),
                        ];
                        ChannelStatistical::create($data);
                    }
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollback();
                }
            }

        }

    }
}
