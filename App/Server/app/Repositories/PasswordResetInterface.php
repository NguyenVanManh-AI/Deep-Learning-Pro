<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface PasswordResetInterface extends RepositoryInterface
{
    public static function findPasswordReset($email, $isUser);

    public static function updateToken($user, $token);

    public static function createToken($email, $token, $isUser);

    public static function findPasswordResetByToken($token);

    public static function deleteUser($user, $isUser);
}
