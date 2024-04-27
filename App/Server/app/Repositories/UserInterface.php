<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface UserInterface extends RepositoryInterface
{
    public static function getAllUsers($filter);
}
