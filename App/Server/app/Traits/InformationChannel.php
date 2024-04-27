<?php

namespace App\Traits;

use App\Models\Content;
use App\Models\User;

trait InformationChannel
{
    public function getIdUserChannel()
    {
        $user = User::find(auth('user_api')->user()->id);
        $idUserChannels = User::where('channel_id', $user->channel_id)->pluck('id')->toArray();

        return $idUserChannels;
    }

    public function getIdUserChannelGood()
    {
        $user = User::find(auth('user_api')->user()->id);
        $idUserChannels = User::where('channel_id', $user->channel_id)
            ->where('is_block', 0)->where('is_delete', 0)->pluck('id')->toArray();

        return $idUserChannels;
    }

    public function getAllIdContentChannel()
    {
        $idUserChannels = $this->getIdUserChannel();
        $idContentChannels = Content::whereIn('user_id', $idUserChannels)->pluck('id')->toArray();

        return $idContentChannels;
    }

    public function getIdContentChannelNoDelete()
    {
        $idUserChannels = $this->getIdUserChannel();
        $idContentChannels = Content::whereIn('user_id', $idUserChannels)->where('is_delete', 0)->pluck('id')->toArray();

        return $idContentChannels;
    }
}
