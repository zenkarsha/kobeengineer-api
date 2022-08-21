<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ClientIdentificationRepository;

class ClientIdentificationViewController extends Controller
{
    protected $repo;

    public function __construct(ClientIdentificationRepository $repo)
    {
        parent::__construct();

        $this->repo = $repo;
    }

    public function home(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $result = $this->repo->getList($current_page);

        return view('dashboard.setting-client-identification', [
            'result' => $result,
        ]);
    }
}
