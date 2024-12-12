<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'url',
        'thumbnail',
        'tags',
        'isPrivate'
    ];

    public function favoritedByUsers() {
        return $this->belongsToMany(User::class, 'video_user_favorites')->withTimestamps();
    }
}
