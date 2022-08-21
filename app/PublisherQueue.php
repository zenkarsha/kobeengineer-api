<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublisherQueue extends Model
{
    protected $table = 'publisher_queue';
    protected $fillable = [
        'post_id',
    ];
}
