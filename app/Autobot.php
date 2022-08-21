<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autobot extends Model
{
    use SoftDeletes;

    protected $table = 'autobot';
    protected $fillable = [
        'name', 'access_token', 'session', 'job',
        'frequency', 'last_poked_at', 'booting',
    ];
}
