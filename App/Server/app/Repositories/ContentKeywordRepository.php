<?php

namespace App\Repositories;

use App\Models\ContentKeyword;

class ContentKeywordRepository extends BaseRepository implements ContentKeywordInterface
{
    public function getModel()
    {
        return ContentKeyword::class;
    }

    // Additional methods can be added here
}
