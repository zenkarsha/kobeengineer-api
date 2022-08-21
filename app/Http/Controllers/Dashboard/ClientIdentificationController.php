<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\ClientIdentificationService;

class ClientIdentificationController extends DashboardController
{
    protected $service;

    public function __construct(ClientIdentificationService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function updateForbidden(Request $request, $id)
    {
        $response = $this->service->updateForbidden($id, $request->get('value'));

        return response()->json($response);
    }
}
