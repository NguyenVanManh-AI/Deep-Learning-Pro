<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddBroadcast;
use App\Http\Requests\RequestChangeIsDeleteBroadcast;
use App\Http\Requests\RequestChangeIsDeleteManyBroadcast;
use App\Http\Requests\RequestSendNow;
use App\Http\Requests\RequestTestSend;
use App\Http\Requests\RequestUpdateBroadcast;
use App\Services\BroadcastService;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    protected BroadcastService $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService = $broadcastService;
    }

    public function addBroadcast(RequestAddBroadcast $request)
    {
        return $this->broadcastService->addBroadcast($request);
    }

    public function getDetailBroadcast(Request $request, $id_broadcast)
    {
        return $this->broadcastService->getDetailBroadcast($request, $id_broadcast);
    }

    public function updateBroadcast(RequestUpdateBroadcast $request, $id_broadcast)
    {
        return $this->broadcastService->updateBroadcast($request, $id_broadcast);
    }

    public function sendNow(RequestSendNow $request)
    {
        return $this->broadcastService->sendNow($request);
    }

    public function getBroadcasts(Request $request)
    {
        return $this->broadcastService->getBroadcasts($request);
    }

    public function testSend(RequestTestSend $request)
    {
        return $this->broadcastService->testSend($request);
    }
    
    public function changeIsDeleteBroadcast(RequestChangeIsDeleteBroadcast $request, $id_broadcast)
    {
        return $this->broadcastService->changeIsDeleteBroadcast($request, $id_broadcast);
    }
    
    public function changeIsDeleteManyBroadcast(RequestChangeIsDeleteManyBroadcast $request)
    {
        return $this->broadcastService->changeIsDeleteManyBroadcast($request);
    }
}
