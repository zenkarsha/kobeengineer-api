<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\DomainBlacklistRepository;

class DomainBlacklistViewController extends Controller
{
    protected $repo;

    public function __construct(DomainBlacklistRepository $repo)
    {
        parent::__construct();

        $this->repo = $repo;
    }

    public function home()
    {
        $result = $this->repo->getAll();

        return view('dashboard.setting-domain-blacklist', [
            'result' => $result,
        ]);
    }
}
