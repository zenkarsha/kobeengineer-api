<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\ClientIdentificationService;

/**
 * @resource Client
 */
class Client extends APICore
{
    protected $clientIdentificationService;

    public function __construct(ClientIdentificationService $clientIdentificationService)
    {
        parent::__construct();

        $this->clientIdentificationService = $clientIdentificationService;
    }

    public function create()
    {
        $response = $this->clientIdentificationService->create();

        return response()->json($response);
    }

    public function check(Requests\ClientCheckRequest $request)
    {
        $response = [
            'success' => true,
            'message' => 'Ok.',
            'code' => 200,
        ];
        return response()->json($response);
    }
}
