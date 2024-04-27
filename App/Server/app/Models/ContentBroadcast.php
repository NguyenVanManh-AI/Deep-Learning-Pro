<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentBroadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'broadcast_id',
        'content_id',
        'content_sticker',
        'sequence',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }
}
