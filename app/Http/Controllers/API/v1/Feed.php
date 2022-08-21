<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIFeedService;

/**
 * @resource Feed
 */
class Feed extends APICore
{
    protected $APIFeedService;

    public function __construct(APIFeedService $APIFeedService)
    {
        parent::__construct();

        $this->APIFeedService = $APIFeedService;
        $this->sorting = ['newest', 'ranking_today', 'ranking_week', 'ranking_month', 'ranking_top100'];
    }

    public function home(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $sort = $request->get('sort');
        if (!in_array($sort, $this->sorting)) $sort = $this->sorting[0];

        $response = $this->APIFeedService->getPosts($sort, $current_page);
        return response()->json($response);
    }

    public function authorFeed(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $author = $request->get('author');

        $response = $this->APIFeedService->getAuthorPosts($author, $current_page);
        return response()->json($response);
    }

    public function recently()
    {
        $response = $this->APIFeedService->getRecentlyPosts();
        return response()->json($response);
    }

    public function authors(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $response = $this->APIFeedService->getAuthors($current_page);

        return response()->json($response);
    }
}
