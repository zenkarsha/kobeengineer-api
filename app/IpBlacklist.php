<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpBlacklist extends Model
{
    protected $table = 'ip_blacklist';
    protected $fillable = [
        'ip', 'forbidden',
    ];
}
