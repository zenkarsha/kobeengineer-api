<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Extensions\UploadHandler;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        return view('dashboard.home', [
            # pass data here
        ]);
    }
}
