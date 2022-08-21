<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $table = 'post_media';
    protected $fillable = [
        'post_id', 'type', 'url',
    ];
}
