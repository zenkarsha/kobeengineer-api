<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;
use App\Repositories\PostImageConfigRepository;
use App\Services\PostService;
use Queue;

class OldPostController extends DashboardController
{
    protected $postRepository;
    protected $publisherQueueRepository;
    protected $postImageConfigRepository;
    protected $postService;

    public function __construct(PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository, PostImageConfigRepository $postImageConfigRepository, PostService $postService)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->postImageConfigRepository = $postImageConfigRepository;
        $this->postService = $postService;
    }

    public function skip($id)
    {
        $response = \DB::table('xx_post')->where('id', $id)->delete();

        return response()->json($response);
    }

    public function deny($id)
    {
        $post = \DB::table('xx_post')->where('id', $id)->first();
        Queue::push('App\Jobs\Analysiser@oldDeny', $post->post_message);
        $response = \DB::table('xx_post')->where('id', $id)->delete();

        return response()->json($response);
    }

    public function allow($id)
    {
        if (\Input::get('content') != '') {
            $content = \Input::get('content');
        }
        else {
            $post = \DB::table('xx_post')->where('id', $id)->first();
            $content = $post->post_message;
        }

        $query_token = $this->postService->generateToken('query_token');
        $delete_token = $this->postService->generateToken('delete_token');
        $data = [
            'key' => createUniqueKey(),
            'type' => 3,
            'content' => $content,
            'hashtag' => '',
            'link' => '',
            'reply_to' => '',
            'queuing' => 1,
            'analysed' => 1,
            'priority' => 0,
            'client_ip' => '127.0.0.1',
            'client_identification' => 0,
            'user_id' => 0,
            'query_token' => $query_token,
            'delete_token' => $delete_token,
            'sync_to_bigplatform' => 0,
        ];

        if ($post_id = $this->postRepository->createGetId($data)) {
            $image_config = [
                'app_key' => '',
                'api_url' => 'https://example.com/api/v2/generator/image/kobeengineer',
                'output' => 'base64',
                'text' => $content,
                'color' => 0,
                'font' => 0,
            ];
            $this->postImageConfigRepository->create([
                'post_id' => $post_id,
                'config' => json_encode($image_config),
            ]);

            $this->publisherQueueRepository->create([
                'post_id' => $post_id,
            ]);
        }

        Queue::push('App\Jobs\Analysiser@oldAllow', $content);
        $response = \DB::table('xx_post')->where('id', $id)->delete();

        return response()->json($response);
    }
}
