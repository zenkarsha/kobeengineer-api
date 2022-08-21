<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;

class PostViewController extends Controller
{
    protected $postRepository;
    protected $publisherQueueRepository;

    public function __construct(PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
    }

    public function overview(Request $request)
    {
        $current_page = $this->getCurrentPage($request);

        # filter script here

        $result = $this->postRepository->getList($current_page, false);

        return view('dashboard.post-overview', [
            'result' => $result,
        ]);
    }

    public function overviewAll(Request $request)
    {
        $current_page = $this->getCurrentPage($request);

        # filter script here

        $result = $this->postRepository->getList($current_page);

        return view('dashboard.post-overview', [
            'result' => $result,
        ]);
    }

    public function pending(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $result = $this->postRepository->getPendingList($current_page);

        return view('dashboard.post-pending', [
            'result' => $result,
        ]);
    }

    public function search(Request $request)
    {
        $current_page = $this->getCurrentPage($request);
        $keyword = $request->get('keyword');
        $state = $request->get('state');
        $type = (int) $request->get('type');

        if (startWith('ip:', $keyword)) {
            $client_ip = substr($keyword, 3);
            $result = $this->postRepository->searchPostsByColumnValue('client_ip', $client_ip, $state, $type, $current_page);
        }
        elseif (startWith('user:', $keyword)) {
            $user_id = substr($keyword, 5);
            $result = $this->postRepository->searchPostsByColumnValue('user_id', $user_id, $state, $type, $current_page);
        }
        elseif (startWith('ci:', $keyword)) {
            $client_identification = substr($keyword, 3);
            $result = $this->postRepository->searchPostsByColumnValue('client_identification', $client_identification, $state, $type, $current_page);
        }
        else {
            $keywords = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
            $result = $this->postRepository->searchPosts($keywords, $state, $type, $current_page);
        }

        return view('dashboard.post-overview', [
            'result' => $result,
            'keyword' => $keyword,
            'state' => $state,
            'type' => $type,
        ]);
    }

    public function queue(Request $request)
    {
        $current_page = $this->getCurrentPage($request);

        $result = $this->publisherQueueRepository->getList($current_page);

        return view('dashboard.post-queue', [
            'result' => $result,
        ]);
    }

    public function create()
    {
        return view('dashboard.post-create', [
            # pass data here
        ]);
    }

    public function restore()
    {
        $post = \DB::table('xx_post')->orderBy('id', 'asc')->first();

        return view('dashboard.post-restore', [
            'post' => $post,
        ]);
    }
}
