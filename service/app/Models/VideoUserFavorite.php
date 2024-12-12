<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoUserFavorite extends Model {
    protected $table = 'video_user_favorites';

    protected $fillable = ['user_id', 'video_id'];
}
