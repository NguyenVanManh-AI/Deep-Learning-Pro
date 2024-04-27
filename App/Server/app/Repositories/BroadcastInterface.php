<?php

namespace App\Repositories;

interface BroadcastInterface extends RepositoryInterface
{
    public static function getBroadcasts($filter);
}
