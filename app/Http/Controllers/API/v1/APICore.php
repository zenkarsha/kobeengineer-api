<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class APICore extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function abort($message = 'Something went wrong.', $code = 550)
    {
        $this->response = [
            'error' => [
                'message' => $message,
                'code' => $code,
            ]
        ];

        return response()->json($this->response);
    }
}
