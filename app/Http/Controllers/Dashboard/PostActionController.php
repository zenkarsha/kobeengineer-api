<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use App\Services\PostActionService;
use App\Services\PostBanService;

class PostActionController extends DashboardController
{
    protected $postService;
    protected $postActionService;
    protected $postBanService;

    public function __construct(PostService $postService, PostActionService $postActionService, PostBanService $postBanService)
    {
        parent::__construct();

        $this->postService = $postService;
        $this->postActionService = $postActionService;
        $this->postBanService = $postBanService;
    }

    public function allow($id)
    {
        $response = $this->postActionService->allow($id);

        return response()->json($response);
    }

    public function deny($id)
    {
        $response = $this->postActionService->denied($id);

        return response()->json($response);
    }

    public function delete($id)
    {
        $response = $this->postActionService->delete($id);

        return response()->json($response);
    }

    public function cancelQueuing($id)
    {
        $response = $this->postActionService->cancelQueuing($id);

        return response()->json($response);
    }

    public function setPriority($id)
    {
        $response = $this->postActionService->setPriority($id);

        return response()->json($response);
    }

    public function unpublish($id)
    {
        $response = $this->postActionService->unpublish($id);

        return response()->json($response);
    }

    public function republish($id)
    {
        $response = $this->postActionService->republish($id);

        return response()->json($response);
    }

    public function ban($id)
    {
        $response = $this->postBanService->ban($id);

        return response()->json($response);
    }

    public function unban($id)
    {
        $response = $this->postBanService->unban($id);

        return response()->json($response);
    }

    public function flagUser($id)
    {
        $response = $this->postBanService->flagUser($id);

        return response()->json($response);
    }

    public function unflagUser($id)
    {
        $response = $this->postBanService->unflagUser($id);

        return response()->json($response);
    }

    public function banUser($id)
    {
        $response = $this->postBanService->banUser($id);

        return response()->json($response);
    }

    public function unbanUser($id)
    {
        $response = $this->postBanService->unbanUser($id);

        return response()->json($response);
    }

    public function banIp($id)
    {
        $response = $this->postBanService->banIp($id);

        return response()->json($response);
    }

    public function unbanIp($id)
    {
        $response = $this->postBanService->unbanIp($id);

        return response()->json($response);
    }

    public function banIpForbidden($id)
    {
        $response = $this->postBanService->banIpForbidden($id);

        return response()->json($response);
    }

    public function banClientIdentification($id)
    {
        $response = $this->postBanService->banClientIdentification($id);

        return response()->json($response);
    }

    public function unbanClientIdentification($id)
    {
        $response = $this->postBanService->unbanClientIdentification($id);

        return response()->json($response);
    }

}
