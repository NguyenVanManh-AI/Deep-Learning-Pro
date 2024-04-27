<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'email',
        'password',
        'line_user_id',
        'channel_id',
        'role',
        'is_delete',
        'is_block',
        'name',
        'avatar',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'token_verify_email',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_verify_email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function broadcasts()
    {
        return $this->hasMany(Broadcast::class);
    }

    public function contentBroadcasts()
    {
        return $this->hasMany(ContentBroadcast::class);
    }

    public function contentKeywords()
    {
        return $this->hasMany(ContentKeyword::class);
    }

    public function interactionLogs()
    {
        return $this->hasMany(InteractionLog::class);
    }
}
