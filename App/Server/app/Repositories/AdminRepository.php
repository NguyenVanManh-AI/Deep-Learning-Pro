<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository extends BaseRepository implements AdminInterface
{
    public function getModel()
    {
        return Admin::class;
    }
}
