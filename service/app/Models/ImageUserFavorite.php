<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageUserFavorite extends Model {
    protected $table = 'image_user_favorites';

    protected $fillable = ['user_id', 'image_id'];
}
