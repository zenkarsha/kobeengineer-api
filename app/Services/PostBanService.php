<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Repositories\ClientIdentificationRepository;
use App\Repositories\IpBlacklistRepository;
use App\Services\PostDomService;

class PostBanService extends Service
{
    protected $postRepository;
    protected $userRepository;
    protected $ipBlacklistRepository;
    protected $clientIdentificationRepository;
    protected $postDomService;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository, IpBlacklistRepository $ipBlacklistRepository, ClientIdentificationRepository $clientIdentificationRepository, PostDomService $postDomService)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->ipBlacklistRepository = $ipBlacklistRepository;
        $this->clientIdentificationRepository = $clientIdentificationRepository;
        $this->postDomService = $postDomService;

        $this->post = null;
    }

    public function ban($post_id)
    {
        $this->post = $this->postRepository->getItem($post_id);

        $this->banUser($post_id);
        $this->banIpForbidden($post_id);
        $this->banClientIdentification($post_id);

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function unban($post_id)
    {
        $this->post = $this->postRepository->getItem($post_id);

        $this->unbanUser($post_id);
        $this->unflagUser($post_id);
        $this->unbanIpForbidden($post_id);
        $this->unbanClientIdentification($post_id);

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function flagUser($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ($this->post->user_id != '0') {
            $this->userRepository->update($this->post->user_id, [
                'flagged' => 1,
                'banned' => 0,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function unflagUser($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ($this->post->user_id != '0') {
            $this->userRepository->update($this->post->user_id, [
                'flagged' => 0,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function banUser($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ((int) $this->post->user_id != 0) {
            $this->userRepository->update($this->post->user_id, [
                'flagged' => 0,
                'banned' => 1,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function unbanUser($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ((int) $this->post->user_id != 0) {
            $this->userRepository->update($this->post->user_id, [
                'banned' => 0,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function banIp($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ($this->post->client_ip != '0') {
            $this->ipBlacklistRepository->firstOrCreate([
                'ip' => $this->post->client_ip,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function unbanIp($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ($this->post->client_ip != '0') {
            $this->ipBlacklistRepository->getItemByIp($this->post->client_ip);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function banIpForbidden($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ($this->post->client_ip != '0') {
            $result = $this->ipBlacklistRepository->firstOrCreate([
                'ip' => $this->post->client_ip,
            ]);

            if ((int) $result->forbidden != 1)
                $this->ipBlacklistRepository->updateColumn($result->id, 'forbidden', 1);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function banClientIdentification($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ((int) $this->post->client_identification != 0) {
            $this->clientIdentificationRepository->update($this->post->client_identification, [
                'forbidden' => 1,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }

    public function unbanClientIdentification($post_id)
    {
        if ($this->post == null)
            $this->post = $this->postRepository->getItem($post_id);

        if ((int) $this->post->client_identification != 0) {
            $this->clientIdentificationRepository->update($this->post->client_identification, [
                'forbidden' => 0,
            ]);
        }

        return $this->successResponse('Ok.', ['dom' => $this->postDomService->reloadPostDom($post_id)]);
    }
}
