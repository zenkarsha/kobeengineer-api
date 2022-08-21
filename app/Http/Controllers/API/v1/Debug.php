<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * @resource Debug
 */
class Debug extends APICore
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Playground
    */
    public function playground()
    {
        // code here
    }
}
