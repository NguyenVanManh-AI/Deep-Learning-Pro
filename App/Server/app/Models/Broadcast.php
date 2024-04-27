<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'line_user_id',
        'title',
        'sent_at',
        'status',
        'is_delete',
        'sender_id',
    ];

    public function contentBroadcasts()
    {
        return $this->hasMany(ContentBroadcast::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
