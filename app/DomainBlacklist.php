<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainBlacklist extends Model
{
    protected $table = 'domain_blacklist';
    protected $fillable = [
        'domain',
    ];

    public $timestamps = false;
}
