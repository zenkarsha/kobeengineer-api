<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIPostService;
use App\Services\APIPostCreateService;

/**
 * @resource Post
 */
class Post extends APICore
{
    protected $APIPostService;
    protected $APIPostCreateService;

    public function __construct(Request $request, APIPostService $APIPostService, APIPostCreateService $APIPostCreateService)
    {
        parent::__construct();

        $this->request = $request;
        $this->APIPostService = $APIPostService;
        $this->APIPostCreateService = $APIPostCreateService;
    }

    public function createPost(Requests\PostCreateRequest $request)
    {
        $response = $this->APIPostCreateService->createPost($request->all());
        return response()->json($response);
    }

    public function getPost(Request $request)
    {
        $response = $this->APIPostService->getPost($request->all());
        return response()->json($response);
    }

    public function getPostComments(Request $request)
    {
        $response = $this->APIPostService->getPostComments($request->all());
        return response()->json($response);
    }
}
