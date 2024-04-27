<?php

namespace App\Repositories;

use App\Models\Channel;

class ChannelRepository extends BaseRepository implements ChannelInterface
{
    public function getModel()
    {
        return Channel::class;
    }

    // Additional methods can be added here
}
