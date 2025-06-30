<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'content',
        'image_path',
    ];

    // Each post belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each post can have many likes
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }

    public function likedBy(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    // Each post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
