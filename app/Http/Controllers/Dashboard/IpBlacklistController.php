<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\IpBlacklistService;

class IpBlacklistController extends DashboardController
{
    protected $service;

    public function __construct(IpBlacklistService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function create(Requests\IpBlacklistRequest $request)
    {
        $response = $this->service->create($request->all());
        if (isset($response['success']))
            return redirect(__('/setting/ip-blacklist'));
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);

        return response()->json($response);
    }

    public function updateForbidden(Request $request, $id)
    {
        $response = $this->service->updateForbidden($id, $request->get('value'));

        return response()->json($response);
    }
}
