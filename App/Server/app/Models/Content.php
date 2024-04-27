<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'content_type',
        'content_data',
        'is_delete',
        'create_id',
        'update_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contentBroadcasts()
    {
        return $this->hasMany(ContentBroadcast::class);
    }

    public function contentKeywords()
    {
        return $this->hasMany(ContentKeyword::class);
    }
}
