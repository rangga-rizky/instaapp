<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['image_url', 'caption'];

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
