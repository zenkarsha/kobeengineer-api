<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainWhitelist extends Model
{
    protected $table = 'domain_whitelist';
    protected $fillable = [
        'domain',
    ];

    public $timestamps = false;
}
