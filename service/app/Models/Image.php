<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model {
    use HasFactory;
    protected $fillable = [
        'url',
    ];

    public function favoritedByUsers() {
        return $this->belongsToMany(User::class, 'image_user_favorites')->withTimestamps();
    }
}
