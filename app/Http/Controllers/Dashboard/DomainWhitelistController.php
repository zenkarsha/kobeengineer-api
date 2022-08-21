<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\DomainWhitelistService;

class DomainWhitelistController extends DashboardController
{
    protected $service;

    public function __construct(DomainWhitelistService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function create(Requests\DomainWhitelistRequest $request)
    {
        $response = $this->service->create($request->all());
        if (isset($response['success']))
            return redirect(__('/setting/domain-whitelist'));
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);

        return response()->json($response);
    }
}
