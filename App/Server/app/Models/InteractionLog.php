<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'timestamp',
        'action_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
