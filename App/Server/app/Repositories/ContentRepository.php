<?php

namespace App\Repositories;

use App\Models\Content;

class ContentRepository extends BaseRepository implements ContentInterface
{
    public function getModel()
    {
        return Content::class;
    }

    public static function getContents($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->join('users', 'users.id', '=', 'contents.user_id')
            ->join('users as updater', 'updater.id', '=', 'contents.update_id')
            ->selectRaw('users.name as creator_name, users.avatar as creator_avatar, 
                updater.name as updater_name, updater.avatar as updater_avatar, contents.*') // lấy ra bằng tên alias (tên đặt lại thì được)
            ->when(isset($filter->search), function ($query) use ($filter) {
                if (!empty($filter->search)) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereRaw("JSON_EXTRACT(content_data, '$.text') LIKE ?", ["%$filter->search%"])
                              ->orWhere('users.name', 'LIKE', '%' . $filter->search . '%')  // nhưng query thì phải lấy tên cột cộng với tên bảng có thể alias 
                              ->orWhere('updater.name', 'LIKE', '%' . $filter->search . '%');
                    });
                }
            })
            ->when(isset($filter->idUserChannels), function ($query) use ($filter) {
                $query->whereIn('contents.user_id', $filter->idUserChannels);
            })
            ->when(isset($filter->content_type), function ($query) use ($filter) {
                if ($filter->content_type !== 'all') {
                    $query->where('contents.content_type', $filter->content_type);
                }
            })
            ->when(isset($filter->is_delete), function ($query) use ($filter) {
                if ($filter->is_delete !== 'all') {
                    $query->where('contents.is_delete', $filter->is_delete);
                }
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
