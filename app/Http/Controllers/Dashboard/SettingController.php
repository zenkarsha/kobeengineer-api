<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Services\FacebookService;


class SettingController extends DashboardController
{
    protected $settingService;
    protected $facebookService;

    public function __construct(SettingService $settingService, FacebookService $facebookService)
    {
        parent::__construct();

        $this->settingService = $settingService;
        $this->facebookService = $facebookService;
    }

    public function create(Requests\SettingCreateRequest $request)
    {
        $response = $this->settingService->create($request->all());
        if (isset($response['success']))
            return redirect(__('/setting'));
    }

    public function save(Request $request)
    {
        $response = $this->settingService->save($request->all());
        if (isset($response['success']))
            return redirect(__('/setting'));
    }

    public function update(Requests\SettingUpdateRequest $request)
    {
        $response = $this->settingService->update($request->all());
        if (isset($response['success']))
            return redirect(__('/setting/advanced-mode'));
    }

    public function delete($id)
    {
        $response = $this->settingService->delete($id);
        if (isset($response['success']))
            return redirect(__('/setting/advanced-mode'));
    }

    public function getPageToken(Requests\SettingGetPageTokenRequest $request)
    {
        $response = $this->facebookService->getPageToken($request->get('access_token'));

        return response()->json($response);
    }
}
