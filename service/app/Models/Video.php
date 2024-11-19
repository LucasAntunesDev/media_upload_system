<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    protected $fillables = [
        'id',
        'name',
        'url',
        'tags',
        'isPrivate'
    ];
}
