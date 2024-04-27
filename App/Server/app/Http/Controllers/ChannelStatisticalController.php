<?php

namespace App\Http\Controllers;
use App\Http\Requests\RequestStatistical;
use App\Services\ChannelStatisticalService;
use Illuminate\Http\Request;

class ChannelStatisticalController extends Controller
{
    protected ChannelStatisticalService $channelStatisticalService;

    public function __construct(ChannelStatisticalService $channelStatisticalService)
    {
        $this->channelStatisticalService = $channelStatisticalService;
    }

    public function Statistical(RequestStatistical $request) {
        return $this->channelStatisticalService->Statistical($request);
    }
}
