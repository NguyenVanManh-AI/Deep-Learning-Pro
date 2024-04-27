<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelStatistical extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'channel_id',
        'date',
        'api_broadcast',
        'api_multicast',
        'followers',
        'targeted_reaches',
        'blocks',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
