<?php

namespace App\Services;

use File;
use Filesystem;
use Storage;

abstract class Service
{
    public function __construct()
    {
        // code here
    }

    public function successResponse($message = 'Ok.', $data = null)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'code' => 200,
        ];

        if ($data != null)
            $response['data'] = $data;

        return $response;
    }

    public function badRequestResponse($message = 'Something wrong.', $code = 400)
    {
        $response = [
            'error' => [
                'message' => $message,
                'code' => $code,
            ]
        ];

        response()->json($response)->send();
        die();
    }
}
