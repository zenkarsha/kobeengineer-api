<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\PostMediaRepository;
use App\Services\PostService;
use Twitter;

class TwitterService extends Service
{
    protected $settingRepository;
    protected $postRepository;
    protected $postPublishedRepository;
    protected $postMediaRepository;
    protected $postService;

    public function __construct(SettingRepository $settingRepository, PostRepository $postRepository, PostPublishedRepository $postPublishedRepository, PostMediaRepository $postMediaRepository, PostService $postService)
    {
        $this->settingRepository = $settingRepository;
        $this->postRepository = $postRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->postMediaRepository = $postMediaRepository;
        $this->postService = $postService;

        $this->facebook_page_name = $this->settingRepository->getValue('facebook_page_name');
    }

    public function publish($id, $true_id)
    {
        $post = $this->postRepository->getItem($id);
        $data = $this->handlePostData($post, $true_id);

        if ($data['type'] == 'image')
            $this->publishImage($data);
        else
            $this->publishText($data);
    }

    public function unpublish($id)
    {
        $post = $this->postRepository->getItem($id);
        $data = $this->postPublishedRepository->getItemsByPostIdAndType($post->id, 'twitter');

        try {
            $callback = Twitter::destroyTweet($data->pid);

            # TODO: check if something can check unpublish success here
            if ($callback)
            {
                $this->postPublishedRepository->deleteByPostIdAndType($post->id, 'twitter');
                if ($post->type == 3)
                    $this->postMediaRepository->deleteByPostIdAndType($post->id, 'twitter');

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Delete post from Twitter failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Delete post from Twitter failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Delete post from Twitter failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Delete post from Twitter failed.');
        }

        return $response;
    }

    public function publishText($data)
    {
        try {
            $callback = Twitter::postTweet([
                'status' => $data['message'],
            ]);

            if (isset($callback->id))
            {
                $twitter_id = $callback->id;
                $this->postPublishedRepository->create([
                    'post_id' => $data['id'],
                    'type' => 'twitter',
                    'success' => 1,
                    'url' => $this->getPostUrl($twitter_id),
                    'pid' => $twitter_id,
                ]);
                $this->postRepository->increasePublished($data['id']);

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Publish to Twitter failed, callback: ' . json_encode($callback));
                $this->logPublishFailed($data['id']);
                $response = $this->badRequestResponse('Publish to Twitter failed.');
            }

        } catch (\Exception $e) {
            \Log::error('Publish to Twitter failed, error: ' . $e->getMessage());
            $this->logPublishFailed($data['id']);
            $response = $this->badRequestResponse('Publish to Twitter failed.');
        }

        return $response;
    }

    public function publishImage($data)
    {
        try {
            $media = Twitter::uploadMedia([
                'media' => file_get_contents($data['image'])
            ]);

            $callback = Twitter::postTweet([
                'status' => $data['message'],
                'media_ids' => $media->media_id_string,
            ]);

            if (isset($callback->id)) {
                $twitter_id = $callback->id;

                $this->postPublishedRepository->create([
                    'post_id' => $data['id'],
                    'type' => 'twitter',
                    'success' => 1,
                    'url' => $this->getPostUrl($twitter_id),
                    'pid' => $twitter_id,
                ]);
                $this->postRepository->increasePublished($data['id']);

                if (isset($callback->entities->media[0]->media_url_https)) {
                    $twitter_media_url = $callback->entities->media[0]->media_url_https;
                    $this->postMediaRepository->create([
                        'post_id' => $data['id'],
                        'type' => 'twitter',
                        'url' => $twitter_media_url,
                    ]);
                }

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Publish to Twitter failed, callback: ' . json_encode($callback));
                $this->logPublishFailed($data['id']);
                $response = $this->badRequestResponse('Publish to Twitter failed.');
            }

        } catch (\Exception $e) {
            \Log::error('Publish to Twitter failed, error: ' . $e->getMessage());
            $this->logPublishFailed($data['id']);
            $response = $this->badRequestResponse('Publish to Twitter failed.');
        }

        return $response;
    }

    private function handlePostData($post, $true_id)
    {
        $data = [
            'id' => $post->id,
            'true_id' => $true_id,
            'facebook_page_name' => $this->facebook_page_name,
        ];

        if ($post->hashtag != '')
            $data['hashtag'] = $post->hashtag;

        switch ((int) $post->type) {
            case 1:
            case 2:
                $data['type'] = 'text';
                $data['message'] = $this->handlePostMessage($post, $true_id);
                break;
            case 3:
                $data['type'] = 'image';
                $data['message'] = $this->handlePostMessage($post, $true_id);
                $data['image'] = $this->postService->getPostImageUrlRand($post->id);
            case 4:
                $data['type'] = 'image';
                $data['message'] = $this->handlePostMessage($post, $true_id);
                $data['image'] = $this->postService->getPostImageUrl($post->id, false, 'imgur');
                break;
        }

        return $data;
    }

    private function handlePostMessage($post, $true_id)
    {
        # TODO: change this to use the post Template instead
        $message = '#' . $this->facebook_page_name . $true_id . ' ';
        # TODO: add submit link here

        if ($post->reply_to != '')
            $message .= 'RE: #' . $this->facebook_page_name . $post->reply_to . ' ';

        if ((int) $post->type != 3) {
            $clear_content = clearStringUrls($post->content);
            if (strlen($message . $clear_content . ' ' . $post->hashtag) > 140) {
                $message = mb_substr($message . $clear_content . ' ' . $post->hashtag, 0, 100, 'UTF-8') . '... ';
                $message .= 'https://example.com/post/?id=' . $true_id;
            }
            else
                $message .= $post->content . ' ' . $post->hashtag;
        }
        else {
            if (strlen($message . $post->hashtag) > 140) {
                $message = mb_substr($message . $post->hashtag, 0, 100, 'UTF-8') . '... ';
                $message .= 'https://example.com/post/?id=' . $true_id;
            }
            else
                $message .= $post->hashtag;
        }

        return $message;
    }

    private function getPostUrl($id)
    {
        return 'https://twitter.com/username/status/' . $id;
    }

    private function logPublishFailed($id)
    {
        return $this->postPublishedRepository->create([
            'post_id' => $id,
            'type' => 'twitter',
            'success' => 0,
        ]);
    }
}
