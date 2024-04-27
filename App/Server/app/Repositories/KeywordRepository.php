<?php

namespace App\Repositories;

use App\Models\Keyword;

class KeywordRepository extends BaseRepository implements KeywordInterface
{
    public function getModel()
    {
        return Keyword::class;
    }

    // Additional methods can be added here
}
