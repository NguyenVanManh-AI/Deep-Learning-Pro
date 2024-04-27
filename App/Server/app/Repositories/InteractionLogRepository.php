<?php

namespace App\Repositories;

use App\Models\InteractionLog;

class InteractionLogRepository extends BaseRepository implements InteractionLogInterface
{
    public function getModel()
    {
        return InteractionLog::class;
    }

    // Additional methods can be added here
}
