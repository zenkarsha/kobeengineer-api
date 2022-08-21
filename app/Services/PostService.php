<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostMediaRepository;
use App\Repositories\PostImageConfigRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\PublisherQueueRepository;
use App\Repositories\SettingRepository;
use App\Services\PostFormService;
use App\Services\BitlyService;
use Queue;

class PostService extends Service
{
    protected $postRepository;
    protected $postMediaRepository;
    protected $postImageConfigRepository;
    protected $postCodeRepository;
    protected $postFormService;
    protected $publisherQueueRepository;
    protected $postPublishedRepository;

    protected $settingRepository;
    protected $bitlyService;

    public function __construct(PostRepository $postRepository, PostMediaRepository $postMediaRepository, PostImageConfigRepository $postImageConfigRepository, PostCodeRepository $postCodeRepository, PostFormService $postFormService, PublisherQueueRepository $publisherQueueRepository, SettingRepository $settingRepository, BitlyService $bitlyService)
    {
        $this->postRepository = $postRepository;
        $this->postFormService = $postFormService;
        $this->postMediaRepository = $postMediaRepository;
        $this->postImageConfigRepository = $postImageConfigRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->settingRepository = $settingRepository;

        $this->bitlyService = $bitlyService;
    }

    public function create($form)
    {
        $type = $this->postFormService->getPostType($form);
        $data = [
            'key' => createUniqueKey(),
            'type' => $type,
            'content' => $form['content'],
            'hashtag' => $this->postFormService->handleHashtag($form['hashtag']),
            'link' => $this->postFormService->getPostLink($form),
            'reply_to' => $form['reply_to'],
            'queuing' => 1,
            'analysed' => 1,
            'priority' => $this->postFormService->getCheckboxValue('priority', $form),
            'client_ip' => '127.0.0.1',
            'client_identification' => 0,
            'user_id' => 0,
            'query_token' => $this->generateToken('query_token'),
            'delete_token' => $this->generateToken('delete_token'),
            'sync_to_bigplatform' => $this->postFormService->getCheckboxValue('sync_to_bigplatform', $form),
        ];

        if ($id = $this->postRepository->createGetId($data))
        {
            if ($type == 3)
                $this->createImageConfig($id, $form['image_config']);
            if ($type == 4)
                $this->createPostCode($id, $form['code']);
            if (array_key_exists('image_url', $form) && $form['image_url'] != '')
                $this->createImgurMedia($id, $form['image_url']);
            $this->pushToPublisherQueue($id);

            return $this->successResponse();
        }
        else
            return $this->badRequestResponse('Setting create failed.');
    }

    public function createImageConfig($id, $image_config)
    {
        return $this->postImageConfigRepository->create([
            'post_id' => $id,
            'config' => $image_config,
        ]);
    }

    public function createPostCode($id, $code)
    {
        return $this->postCodeRepository->create([
            'post_id' => $id,
            'code' => $code,
        ]);
    }

    public function createImgurMedia($id, $image_url)
    {
        return $this->postMediaRepository->create([
            'post_id' => $id,
            'type' => 'imgur',
            'url' => $image_url,
        ]);
    }

    public function pushToPublisherQueue($id)
    {
        return $this->publisherQueueRepository->create([
            'post_id' => $id,
        ]);
    }

    public function getPostImageUrl($post_id, $allow_empty = false, $type = '')
    {
        $result = $this->postMediaRepository->getItemByPostId($post_id, $type);
        if (count($result) > 0)
            return $result->url;
        else {
            if ($allow_empty)
                return '';
            else
                return $this->getPostImageUrlRand($post_id);
        }
    }

    public function getPostImageUrlRand($post_id)
    {
        $post = $this->postRepository->getItem($post_id);
        $domain = randDomain($this->settingRepository->getValue('publisher_image_domains'));
        $rand_subdomain = randString(32);
        $url = 'http://' . $rand_subdomain . '.' . $domain . '/v1/post/image/' . $post->key . '?query_token=' . $post->query_token;

        return $url;
    }

    public function getPostRedirectUrl($url, $bitly = true)
    {
        $domain = randDomain($this->settingRepository->getValue('publisher_redirect_domains'));
        $url = simpleUrl($url);
        $url = str_replace('.', '+d+', $url);
        $url = urlencode($url);
        $escape_url = $domain . '/' . md5(date("gaFjYHi")) . '?url=' . $url;

        if ($bitly) {
            $response = $this->bitlyService->create($escape_url);
            if (isset($response['data']['url'])) {
                $escape_url = simpleUrl($response['data']['url']);
            }
        }

        return $escape_url;
    }

    public function generateToken($field_key)
    {
        do {
            $token = randString(32);
        } while ($this->checkFieldExists($field_key, $token));

        return $token;
    }

    public function checkFieldExists($field_key, $value)
    {
        $count = $this->postRepository->getFieldCount($field_key, $value);
        if ($count > 0)
            return true;
        return false;
    }
}
