<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository implements UserInterface
{
    public function getModel()
    {
        return User::class;
    }

    /**
     * getAllAdmin
     *
     * @return array
     */
    public static function getAllUsers($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('phone', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('address', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('date_of_birth', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('line_user_id', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(isset($filter->channel_id), function ($query) use ($filter) {
                $query->where('users.channel_id', $filter->channel_id);
            })
            ->when(isset($filter->role), function ($query) use ($filter) {
                if ($filter->role !== 'all') {
                    $query->where('users.role', $filter->role);
                }
            })
            ->when(isset($filter->is_delete), function ($query) use ($filter) {
                if ($filter->is_delete !== 'all') {
                    $query->where('users.is_delete', $filter->is_delete);
                }
            })
            ->when(isset($filter->is_block), function ($query) use ($filter) {
                if ($filter->is_block !== 'all') {
                    $query->where('users.is_block', $filter->is_block);
                }
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
