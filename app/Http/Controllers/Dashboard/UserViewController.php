<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserViewController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function overview(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $result = $this->userRepository->getList($current_page);

        return view('dashboard.user-overview', [
            'result' => $result,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        $current_page = $this->getCurrentPage($request);
        $result = $this->userRepository->getSearchList($keyword, $current_page);

        return view('dashboard.user-overview', [
            'result' => $result,
            'keyword' => $keyword
        ]);
    }
}
