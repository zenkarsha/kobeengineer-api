<?php

namespace App\Services;

use App\Repositories\PostRepository;
use View;

class PostDomService extends Service
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function reloadPostDom($id)
    {
        $result = $this->postRepository->getItem($id);

        $data = [
            'item' => $result,
        ];
        $view = View::make('partial.dashboard.overview.post-card', $data);

        return $view->render();
    }
}
