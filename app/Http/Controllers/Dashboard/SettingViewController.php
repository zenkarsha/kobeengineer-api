<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\SettingRepository;

class SettingViewController extends Controller
{
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        parent::__construct();

        $this->settingRepository = $settingRepository;
    }

    public function home()
    {
        $result = $this->settingRepository->getAll();
        $facebook_app_id = $this->settingRepository->getValue('facebook_app_id');

        return view('dashboard.setting-home', [
            'result' => $result,
            'facebook_app_id' => $facebook_app_id,
        ]);
    }

    public function advancedMode()
    {
        $result = $this->settingRepository->getAll();

        return view('dashboard.setting-advanced-mode', [
            'result' => $result,
        ]);
    }

    public function create()
    {
        return view('dashboard.setting-create', [
            //
        ]);
    }

    public function edit($id)
    {
        $result = $this->settingRepository->getItem($id);

        return view('dashboard.setting-edit', [
            'result' => $result,
        ]);
    }
}
