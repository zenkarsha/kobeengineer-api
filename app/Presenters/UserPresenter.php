<?php

namespace App\Presenters;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Repositories\IpBlacklistRepository;
use App\Repositories\ClientIdentificationRepository;

class UserPresenter
{
    protected $userRepository;
    protected $postRepository;
    protected $ipBlacklistRepository;
    protected $clientIdentificationRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository, IpBlacklistRepository $ipBlacklistRepository, ClientIdentificationRepository $clientIdentificationRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->ipBlacklistRepository = $ipBlacklistRepository;
        $this->clientIdentificationRepository = $clientIdentificationRepository;
    }

    public function getPublicText($value)
    {
        if ((int) $value == 1)
            return '公開帳戶';
        else
            return '未公開';
    }

    public function checkUserFlagState($user_id)
    {
        $result = $this->userRepository->getItem($user_id);
        var_dump($result);
        return (count(array($result)) && $result != null && (int) $result->flagged == 1) ? true : false;
    }

    public function checkUserBanState($user_id)
    {
        $result = $this->userRepository->getItem($user_id);
        return (count(array($result)) && $result != null && (int) $result->banned == 1) ? true : false;
    }

    public function checkIpBanState($ip)
    {
        $result = $this->ipBlacklistRepository->getItemByIp($ip);
        return count(array($result)) ? true : false;
    }

    public function checkClientIdentificationBanState($user_id)
    {
        $result = $this->clientIdentificationRepository->getItem($user_id);
        return (count(array($result)) && $result != null && (int) $result->forbidden == 1) ? true : false;
    }

    public function getUserPostCount($user_id)
    {
        return $this->postRepository->countUserPosts($user_id);
    }

}
