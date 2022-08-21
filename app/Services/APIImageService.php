<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostImageConfigRepository;
use App\Repositories\PostCodeRepository;

class APIImageService extends PostService
{
    protected $postRepository;
    protected $postImageConfigRepository;
    protected $postCodeRepository;

    public function __construct(PostRepository $postRepository, PostImageConfigRepository $postImageConfigRepository, PostCodeRepository $postCodeRepository)
    {
        $this->postRepository = $postRepository;
        $this->postImageConfigRepository = $postImageConfigRepository;
        $this->postCodeRepository = $postCodeRepository;
    }

    public function genPostImage($key, $query_token)
    {
        $post = $this->postRepository->getItemByKey($key);
        if (count($post) == 0) return abort(404);
        if ($post->query_token != $query_token) return abort(404);
        if ((int) $post->type != 3 && (int) $post->type != 4) return abort(404);

        if ((int) $post->type == 3) {
            $result = $this->postImageConfigRepository->getItemByPostId($post->id);
            $config = json_decode($result->config);

            try {
                $response = json_decode(postRequest($config->api_url, $config));
                if ($response->success)
                {
                    $image = base64ToImage($response->image);
                    header("Content-Type: image/png");
                    imagepng($image);
                }
                else
                    $this->badRequestResponse('HTTP request failed!', 521);
            } catch (\Exception $e) {
                $this->badRequestResponse('HTTP request failed!', 521);
            }
        }
        elseif ((int) $post->type == 4) {
            $code = $this->postCodeRepository->getValueByPostId($post->id);
            $data = [
                'code' => $code,
                'language' => 'js',
                'theme' => 'monokai',
            ];

            try {
                $base64 = code2image($data);
                $image = imagecreatefromstring(base64_decode($base64));
                header("Content-Type: image/png");
                imagepng($image);
            } catch (\Exception $e) {
                $this->badRequestResponse('HTTP request failed!', 521);
            }
        }
    }

    public function genCodeImage($form)
    {
        $data = [
            'code' => $form('code'),
            'language' => $form('language'),
            'theme' => $form('theme'),
        ];

        $image = code2image($data);
        $response = [
            'success' => true,
            'image' => $image,
            'code' => 200,
        ];

        return response()->json($response);
    }
}
