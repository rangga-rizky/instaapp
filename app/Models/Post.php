<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['caption', 'user_id', 'image_url'];

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
