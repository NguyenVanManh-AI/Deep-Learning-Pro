<?php

namespace App\Repositories;

use App\Models\Broadcast;

class BroadcastRepository extends BaseRepository implements BroadcastInterface
{
    public function getModel()
    {
        return Broadcast::class;
    }

    public static function getBroadcasts($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->join('users', 'users.id', '=', 'broadcasts.sender_id')
            ->selectRaw('users.name as sender_name, users.avatar as sender_avatar, broadcasts.*')
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('users.name', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('broadcasts.title', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(isset($filter->idUserChannels), function ($query) use ($filter) {
                $query->whereIn('broadcasts.sender_id', $filter->idUserChannels);
            })
            ->when(isset($filter->status), function ($query) use ($filter) {
                if ($filter->status !== 'all') {
                    $query->where('broadcasts.status', $filter->status);
                }
            })
            ->when(isset($filter->is_delete), function ($query) use ($filter) {
                if ($filter->is_delete !== 'all') {
                    $query->where('broadcasts.is_delete', $filter->is_delete);
                }
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
