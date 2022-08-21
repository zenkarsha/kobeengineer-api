<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIReactionService;

/**
 * @resource Reaction
 */
class Reaction extends APICore
{
    protected $APIReactionService;

    public function __construct(Request $request, APIReactionService $APIReactionService)
    {
        parent::__construct();

        $this->request = $request;
        $this->APIReactionService = $APIReactionService;
    }

    public function likePost($id)
    {
        $response = $this->APIReactionService->likePost($id);
        return response()->json($response);
    }

    public function commentPost($id)
    {
        $response = $this->APIReactionService->commentPost($id);
        return response()->json($response);
    }

    public function likeComment($id)
    {
        $response = $this->APIReactionService->likeComment($id);
        return response()->json($response);
    }
}
