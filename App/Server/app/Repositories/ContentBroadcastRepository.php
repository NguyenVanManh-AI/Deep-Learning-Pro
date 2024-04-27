<?php

namespace App\Repositories;

use App\Models\ContentBroadcast;

class ContentBroadcastRepository extends BaseRepository implements ContentBroadcastInterface
{
    public function getModel()
    {
        return ContentBroadcast::class;
    }

    // Additional methods can be added here
}
