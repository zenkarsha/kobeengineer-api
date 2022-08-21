<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\AutobotService;

class AutobotController extends Controller
{
    protected $autobotService;

    public function __construct(Request $request, AutobotService $autobotService)
    {
        parent::__construct();

        $this->request = $request;
        $this->autobotService = $autobotService;
    }

    public function poke($id)
    {
        $session = $this->request->get('session');
        $access_token = $this->request->get('access_token');
        $response = $this->autobotService->poke($id, $session, $access_token);

        return response()->json($response);
    }
}
