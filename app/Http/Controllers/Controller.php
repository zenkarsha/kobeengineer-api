<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $allowed_hosts = [
          'example.com',
          'localhost',
        ];

        $this->response = [
            'error' => [
                'message' => 'Permission denied.',
                'code' => 550,
            ]
        ];

        if (!originalCheck($allowed_hosts)) {
            return response()->json($this->response);
            exit;
        }
    }

    public function getCurrentPage($request)
    {
        return (int) $request->input('page') > 0 ? (int) $request->input('page') : 1;
    }
}
