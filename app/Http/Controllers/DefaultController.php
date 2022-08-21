<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;

class DefaultController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deny()
    {
        return response()->json($this->response);
    }
}
