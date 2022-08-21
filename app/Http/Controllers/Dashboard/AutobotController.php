<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\AutobotService;

class AutobotController extends DashboardController
{
    protected $autobotService;

    public function __construct(AutobotService $autobotService)
    {
        parent::__construct();

        $this->autobotService = $autobotService;
    }

    public function create(Requests\AutobotCreateRequest $request)
    {
        $response = $this->autobotService->create($request->all());
        if (isset($response['success']))
            return redirect(__('/autobot/overview'));
    }

    public function update(Requests\AutobotUpdateRequest $request)
    {
        $response = $this->autobotService->update($request->all());
        if (isset($response['success']))
            return redirect(__('/autobot/overview'));
    }

    public function delete($id)
    {
        $response = $this->autobotService->delete($id);
        return response()->json($response);
    }

    public function boot(Request $request, $id)
    {
        $value = (int) $request->get('value') == 1 ? 1 : 0;
        $response = $this->autobotService->boot($id, $value);

        return response()->json($response);
    }

    public function reboot($id)
    {
        $response = $this->autobotService->reboot($id);

        return response()->json($response);
    }
}
