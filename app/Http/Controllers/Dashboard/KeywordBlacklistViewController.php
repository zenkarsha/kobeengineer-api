<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\KeywordBlacklistRepository;

class KeywordBlacklistViewController extends Controller
{
    protected $repo;

    public function __construct(KeywordBlacklistRepository $repo)
    {
        parent::__construct();

        $this->repo = $repo;
    }

    public function home()
    {
        $result = $this->repo->getAll();

        return view('dashboard.setting-keyword-blacklist', [
            'result' => $result,
        ]);
    }
}
