<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BitlyAccountService;

class BitlyAccountController extends DashboardController
{
    protected $service;

    public function __construct(BitlyAccountService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function create(Requests\BitlyAccountRequest $request)
    {
        $response = $this->service->create($request->all());
        if (isset($response['success']))
            return redirect(__('/setting/bitly-account'));
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);

        return response()->json($response);
    }
}
