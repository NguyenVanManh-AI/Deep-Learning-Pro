<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'account_manager_id',
        'channel_id',
        'channel_name',
        'channel_secret',
        'channel_access_token',
        'picture_url',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function channelStatisticals()
    {
        return $this->hasMany(ChannelStatistical::class);
    }
}
