<?php

namespace App\Repositories;

use App\Models\ChannelStatistical;

class ChannelStatisticalRepository extends BaseRepository implements ChannelStatisticalInterface
{
    public function getModel()
    {
        return ChannelStatistical::class;
    }

    // Additional methods can be added here
}
