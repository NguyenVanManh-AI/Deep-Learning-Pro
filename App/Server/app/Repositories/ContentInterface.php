<?php

namespace App\Repositories;

interface ContentInterface extends RepositoryInterface
{
    public static function getContents($filter);
}
