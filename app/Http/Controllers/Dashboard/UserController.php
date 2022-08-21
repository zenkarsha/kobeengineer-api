<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends DashboardController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function flag(Request $request, $id)
    {
        $value = (int) $request->get('value') == 1 ? 1 : 0;
        $response = $this->userRepository->update($id, ['flagged' => $value]);

        return response()->json($response);
    }

    public function ban(Request $request, $id)
    {
        $value = (int) $request->get('value') == 1 ? 1 : 0;
        $response = $this->userRepository->update($id, ['banned' => $value]);

        return response()->json($response);
    }
}
