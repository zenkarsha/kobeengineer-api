<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostPublishedRepository;
use App\Services\PostService;
use Facebook;

class FacebookService extends Service
{
    protected $settingRepository;
    protected $postRepository;
    protected $postPublishedRepository;
    protected $postService;

    public function __construct(SettingRepository $settingRepository, PostRepository $postRepository, PostPublishedRepository $postPublishedRepository, PostService $postService)
    {
        $this->settingRepository = $settingRepository;
        $this->postRepository = $postRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->postService = $postService;

        $this->facebook_page_name = $this->settingRepository->getValue('facebook_page_name');
        $this->facebook_page_id = $this->settingRepository->getValue('facebook_page_id');
        $this->facebook_page_token = $this->settingRepository->getValue('facebook_page_token');
        $this->facebook_app_id = $this->settingRepository->getValue('facebook_app_id');
        $this->facebook_app_secret = $this->settingRepository->getValue('facebook_app_secret');

        $this->facebook_config = [
            'http_client_handler' => 'stream',
            'app_id' => $this->facebook_app_id,
            'app_secret' => $this->facebook_app_secret,
            'default_graph_version' => 'v14.0',
            'default_access_token' => $this->facebook_page_token,
        ];
    }

    public function publish($id, $true_id)
    {
        $post = $this->postRepository->getItem($id);
        $data = $this->handlePostData($post, $true_id);

        $fb = new Facebook($this->facebook_config);
        $target = $this->handlePublishTarget($data['type']);

        try {
            $callback = $fb->post($target, $data);
            $callback = $callback->getDecodedBody();

            if (isset($callback['id'])) {
                $facebook_id = $callback['id'];
                if ($data['type'] == 'image' || $data['type'] == 'code')
                    $facebook_id = $callback['post_id'];

                $this->postPublishedRepository->create([
                    'post_id' => $id,
                    'type' => 'facebook',
                    'success' => 1,
                    'url' => $this->getPostUrl($facebook_id),
                    'pid' => $facebook_id,
                ]);
                $this->postRepository->increasePublished($id);

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Publish to Facebook failed, callback: ' . json_encode($callback));
                $this->logPublishFailed($id);
                $response = $this->badRequestResponse('Publish to Facebook failed, callback: ' . json_encode($callback));
            }
        }
        catch (\Exception $e) {
            \Log::error('Publish to Facebook failed, error: ' . $e->getMessage());
            $this->logPublishFailed($id);
            $response = $this->badRequestResponse('Publish to Facebook failed.');
        }

        return $response;
    }

    public function unpublish($id)
    {
        $post = $this->postRepository->getItem($id);
        $data = $this->postPublishedRepository->getItemsByPostIdAndType($post->id, 'facebook');

        $fb = new Facebook($this->facebook_config);
        try {
            $callback = $fb->delete('/' . $data->pid);
            $callback = $callback->getDecodedBody();

            if ($callback['success'] == 1)
            {
                $this->postPublishedRepository->deleteByPostIdAndType($post->id, 'facebook');
                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Delete post from Facebook failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Delete post from Facebook failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Delete post from Facebook failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Delete post from Facebook failed.');
        }

        return $response;
    }

    public function getPageToken($fb_exchange_token)
    {
        $long_terms_token = $this->getLongTermsToken($this->facebook_app_id, $this->facebook_app_secret, $fb_exchange_token);
        $this->facebook_config['default_access_token'] = $long_terms_token;

        $fb = new Facebook($this->facebook_config);

        try {
            $callback = $fb->get('/me/accounts/');
            $callback = $callback->getDecodedBody();

            if ($callback['data']) {
                foreach ($callback['data'] as $page) {
                    if ($page['id'] == $this->facebook_page_id) {
                        $response = $this->successResponse('Ok.', ['access_token' => $page['access_token']]);
                    }
                }
            }
            else {
                $response = $this->badRequestResponse('Request failed when try to get Facebook /me/accounts');
            }
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
            $response = $this->badRequestResponse('Request failed when try to get Facebook /me/accounts');
        }

        return $response;
    }

    public function getPostComments($pid, $after = '')
    {
        $fb = new Facebook($this->facebook_config);

        $target = '/' . $pid . '/comments';
        if ($after != '')
            $target .= '?after=' . $after;

        try {
            $callback = $fb->get($target);
            $callback = $callback->getDecodedBody();

            $data = [
                'comments' => $callback['data']
            ];

            if (isset($callback['paging']['cursors']['after'])) {
                $data['after'] = $callback['paging']['cursors']['after'];
            }

            return $this->successResponse('Ok.', $data);
        }
        catch (\Exception $e) {
            $data = [
                'comments' => [],
            ];
            return $this->successResponse('Ok.', $data);
        }
    }

    private function getLongTermsToken($facebook_app_id, $facebook_app_secret, $fb_exchange_token)
    {
        $url = 'https://graph.facebook.com/oauth/access_token?client_id=' . $facebook_app_id . '&client_secret=' . $facebook_app_secret . '&grant_type=fb_exchange_token&fb_exchange_token=' . $fb_exchange_token;

        $query = file_get_contents($url);
        $object = json_decode($query);
        $access_token = $object->access_token;

        return $access_token;
    }

    private function handlePostData($post, $true_id)
    {
        $data = [
            'post_id' => $post->id,
            'true_id' => $true_id,
            'type' => $this->getPostType($post),
        ];

        $content = $this->handlePostContent($post, $true_id);
        switch ($data['type']) {
            case 'image':
                // $data['url'] = $this->postService->getPostImageUrlRand($data['post_id']);
                $data['url'] = $this->postService->getPostImageUrl($data['post_id'], false, 'imgur');
                $data['caption'] = $content;
                break;
            case 'code':
                $data['url'] = $this->postService->getPostImageUrl($data['post_id'], false, 'imgur');
                $data['caption'] = $content;
                break;
            case 'link':
                $data['link'] = $post->link;
                $data['message'] = $content;
                break;
            default:
                $data['message'] = $content;
                break;
        }

        return $data;
    }

    private function getPostType($post)
    {
        switch ((int) $post->type) {
            case 2:
                return 'link'; break;
            case 3:
                return 'image'; break;
            case 4:
                return 'code'; break;
            default:
                return 'text'; break;
        }
    }

    private function handlePublishTarget($type)
    {
        switch ($type) {
            case 'image':
            case 'code':
                return '/' . $this->facebook_page_id . '/photos'; break;
            default:
                return '/me/feed/'; break;
        }
    }

    private function handlePostContent($post, $true_id)
    {
        $message = '#' . $this->facebook_page_name . $true_id;

        if ($post->reply_to != '')
            $message .= ' RE: #' . $this->facebook_page_name . $post->reply_to;

        $publish_url = 'https://example.com';
        $publish_url = $this->postService->getPostRedirectUrl($publish_url);
        $message .= "\n📢匿名發文 " . $publish_url;
        $report_url = 'https://example.com/report/?id=' . $true_id;
        // // 檢舉連結
        // $report_url = $this->postService->getPostRedirectUrl($report_url);
        // $message .= "\n👎檢舉本篇 " . $report_url;

        if ((int) $post->type != 3)
            $message .= "\n\n" . $post->content;

        if ((int) $post->type == 3) {
            $links = getUrlFromString($post->content);
            if (count($links) > 0)
                $message .= "\n\n" .implode(' ', $links);
        }

        if ($post->hashtag != '')
            $message .= "\n\n" . $post->hashtag;

        return $message;
    }

    private function getPostUrl($id)
    {
        return 'https://facebook.com/' . $id;
    }

    private function logPublishFailed($id)
    {
        return $this->postPublishedRepository->create([
            'post_id' => $id,
            'type' => 'facebook',
            'success' => 0,
        ]);
    }
}
