<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class ExampleRepository.
 */
class PasswordResetRepository extends BaseRepository implements PasswordResetInterface
{
    public function getModel()
    {
        return PasswordReset::class;
    }

    /**
     * findPasswordReset
     *
     * @param string $email
     * @return object
     */
    public static function findPasswordReset($email, $isUser)
    {
        return (new self)->model->where('email', '=', $email)
            ->where('is_user', '=', $isUser)
            ->first();
    }

    /**
     * findPasswordResetByToken
     *
     * @param string $token
     * @return object
     */
    public static function findPasswordResetByToken($token)
    {
        return (new self)->model
            ->when($token, fn ($q) => $q->where('token', '=', $token))
            ->first();
    }

    /**
     * updateToken
     *
     * @param object $user
     * @param string $token
     */
    public static function updateToken($user, $token)
    {
        DB::beginTransaction();
        try {
            $user = (new self)->model->find($user->id);
            $updateData = [
                'token' => $token,
            ];
            $user->update($updateData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * createToken
     *
     * @param string $email
     * @param string $token
     * @return object
     */
    public static function createToken($email, $token, $isUser)
    {
        DB::beginTransaction();
        try {
            $newToken = (new self)->model->create([
                'email' => $email,
                'is_user' => $isUser,
                'token' => $token,
            ]);
            DB::commit();

            return $newToken;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * deleteUser
     *
     * @param object $user
     */
    public static function deleteUser($user, $isUser)
    {
        DB::beginTransaction();
        try {
            $user = (new self)->model
                ->when($user->email, fn ($q) => $q->where('email', '=', $user->email))
                ->when($isUser, fn ($q) => $q->where('is_user', '=', $isUser))
                ->first();
            $user->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
