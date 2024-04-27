<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'content_id',
        'keyword_id',
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

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
