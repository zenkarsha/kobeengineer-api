<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\AutobotRepository;

class AutobotViewController extends Controller
{
    protected $autobotRepository;

    public function __construct(AutobotRepository $autobotRepository)
    {
        parent::__construct();

        $this->autobotRepository = $autobotRepository;
    }

    public function overview(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $result = $this->autobotRepository->getList($current_page);

        return view('dashboard.autobot-overview', [
            'result' => $result,
        ]);
    }

    public function create()
    {
        return view('dashboard.autobot-create', [
            //
        ]);
    }

    public function edit($id)
    {
        $result = $this->autobotRepository->getItem($id);

        return view('dashboard.autobot-edit', [
            'result' => $result,
        ]);
    }
}
