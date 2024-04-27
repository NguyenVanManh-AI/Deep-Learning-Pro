<?php

namespace App\Services;

use App\Http\Requests\RequestStatistical;
use App\Models\ChannelStatistical;
use App\Models\Content;
use App\Models\User;
use App\Repositories\ChannelStatisticalInterface;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Throwable;

class ChannelStatisticalService
{
    use APIResponse;

    protected ChannelStatisticalInterface $channelStatisticalRepository;

    public function __construct(
        ChannelStatisticalInterface $channelStatisticalRepository,
    ) {
        $this->channelStatisticalRepository = $channelStatisticalRepository;
    }

    public function Statistical(RequestStatistical $request) {
        DB::beginTransaction();
        try {
    
            if ($request->start_date) $startDate = Carbon::parse($request->start_date);
            else $startDate = Carbon::parse(ChannelStatistical::orderBy('date', 'asc')->value('date'));
    
            if ($request->end_date) $endDate = Carbon::parse($request->end_date);
            else $endDate = Carbon::parse(Carbon::now()->format('Y-m-d'));
    
            $data = [];
            $data['start_date'] = $startDate->format('Y-m-d');  
            $data['end_date'] = $endDate->format('Y-m-d');  

            $manager = User::find(auth('user_api')->user()->id);
            $bar_chart = [
                'label' => [],
                'api_broadcast' => [],
                'api_multicast' => [],
                'followers' => [],
                'targeted_reaches' => [],
                'blocks' => [],
            ];
            $collection = ChannelStatistical::whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->where('channel_id', $manager->channel_id);
            
            $dates = [];
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dates[] = $date->toDateString();
            }
    
            $bar_chart['label'] = $dates;
    
            $metrics = ['api_broadcast', 'api_multicast', 'followers', 'targeted_reaches', 'blocks'];
    
            foreach ($metrics as $metric) {
                $result = $collection->pluck($metric, 'date')->toArray();
                foreach ($dates as $date) {
                    $value = array_key_exists($date, $result) ? $result[$date] : 0;
                    $bar_chart[$metric][] = $value;
                }
            }
    
            $data['bar_chart'] = $bar_chart;

            $doughnut_chart = [
                'message' => [],
                'followers' => [],
                'member' => [],
                'content' => [],
                'content_delete' => [],
            ];
            $doughnut_chart['message']['label'] = ['Api Broadcast', 'Api Multicast'];
            $doughnut_chart['message']['data'] = [];
            $doughnut_chart['message']['data'][] = array_sum($bar_chart['api_broadcast']);
            $doughnut_chart['message']['data'][] = array_sum($bar_chart['api_multicast']);

            $doughnut_chart['followers']['label'] = ['Followers', 'Targeted Reaches', 'Blocks'];
            $doughnut_chart['followers']['data'][] = array_sum($bar_chart['followers']);
            $doughnut_chart['followers']['data'][] = array_sum($bar_chart['targeted_reaches']);
            $doughnut_chart['followers']['data'][] = array_sum($bar_chart['blocks']);

            // member
            $doughnut_chart['member']['label'] = ['Deleted member', 'Normal member'];
            $doughnut_chart['member']['data'] = [];
            $doughnut_chart['member']['data'][] = User::where('channel_id', $manager->channel_id)->where('is_delete', 1)->count();
            $doughnut_chart['member']['data'][] = User::where('channel_id', $manager->channel_id)->where('is_delete', 0)->count();

            // content 
            $idUserChannels = User::where('channel_id', $manager->channel_id)->pluck('id')->toArray();
            $doughnut_chart['content']['label'] = ['TEXT', 'STICKER', 'IMAGE'];
            $doughnut_chart['content']['data'] = [];
            $doughnut_chart['content']['data'][] = Content::whereIn('user_id', $idUserChannels)->where('content_type', 'text')->count();
            $doughnut_chart['content']['data'][] = Content::whereIn('user_id', $idUserChannels)->where('content_type', 'sticker')->count();
            $doughnut_chart['content']['data'][] = Content::whereIn('user_id', $idUserChannels)->where('content_type', 'image')->count();

            // content_delete
            $doughnut_chart['content_delete']['label'] = ['DELETE', 'NORMAL'];
            $doughnut_chart['content_delete']['data'] = [];
            $doughnut_chart['content_delete']['data'][] = Content::whereIn('user_id', $idUserChannels)->where('is_delete', 1)->count();
            $doughnut_chart['content_delete']['data'][] = Content::whereIn('user_id', $idUserChannels)->where('is_delete', 0)->count();

            $data['doughnut_chart'] = $doughnut_chart;
    
            DB::commit();
    
            return $this->responseSuccessWithData($data, 'Statistical Channel successful !');
        } catch (Throwable $e) {
            DB::rollback();
    
            return $this->responseError($e->getMessage(), 400);
        }
    }
    
    

}
