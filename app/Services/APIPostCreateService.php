<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostMediaRepository;
use App\Repositories\PostImageConfigRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\ClientIdentificationRepository;
use App\Repositories\IpBlacklistRepository;
use App\Repositories\KeywordBlacklistRepository;
use App\Repositories\DomainWhitelistRepository;
use App\Repositories\DomainBlacklistRepository;
use App\Repositories\SettingRepository;
use App\Services\PinyinService;
use App\Services\PostService;
use App\Services\PostFormService;
use Auth;

class APIPostCreateService extends Service
{
    protected $postRepository;
    protected $postMediaRepository;
    protected $postImageConfigRepository;
    protected $postCodeRepository;
    protected $clientIdentificationRepository;
    protected $ipBlacklistRepository;
    protected $keywordBlacklistRepository;
    protected $domainWhitelistRepository;
    protected $domainBlacklistRepository;
    protected $settingRepository;
    protected $pinyinService;
    protected $postService;
    protected $postFormService;

    public function __construct(PostRepository $postRepository, PostMediaRepository $postMediaRepository, PostImageConfigRepository $postImageConfigRepository, PostCodeRepository $postCodeRepository, ClientIdentificationRepository $clientIdentificationRepository, IpBlacklistRepository $ipBlacklistRepository, KeywordBlacklistRepository $keywordBlacklistRepository, DomainWhitelistRepository $domainWhitelistRepository, DomainBlacklistRepository $domainBlacklistRepository, SettingRepository $settingRepository, PinyinService $pinyinService, PostService $postService, PostFormService $postFormService)
    {
        $this->postRepository = $postRepository;
        $this->postMediaRepository = $postMediaRepository;
        $this->postImageConfigRepository = $postImageConfigRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->clientIdentificationRepository = $clientIdentificationRepository;
        $this->ipBlacklistRepository = $ipBlacklistRepository;
        $this->keywordBlacklistRepository = $keywordBlacklistRepository;
        $this->domainWhitelistRepository = $domainWhitelistRepository;
        $this->domainBlacklistRepository = $domainBlacklistRepository;
        $this->settingRepository = $settingRepository;

        $this->pinyinService = $pinyinService;
        $this->postService = $postService;
        $this->postFormService = $postFormService;

        $this->user = Auth::user();
    }

    public function createPost($form)
    {
        # TODO: clear code
        // if ((int) $this->user->verified != 1) {
        //     \Log::warning($this->user->name . ' not verified user try to create post.');
        //     return $this->badRequestResponse('Request deined.');
        //     exit;
        // }

        if ((int) $this->user->banned == 1) {
            return $this->badRequestResponse('Request deined.');
            exit;
        }

        $client_ip = getUserIP();
        $client_identification = $this->getClientIdentification($form['identification']);

        $key = createUniqueKey();
        $type = $this->postFormService->getPostType($form);
        $hashtag = $this->postFormService->handleHashtag($form['hashtag']);
        $link = $this->postFormService->getPostLink($form);
        $query_token = $this->postService->generateToken('query_token');
        $delete_token = $this->postService->generateToken('delete_token');

        # TODO: frontend need add checkbox field
        // $sync_to_bigplatform = $this->postFormService->getCheckboxValue('sync_to_bigplatform', $form);
        $sync_to_bigplatform = 0;

        $data = [
            'key' => $key,
            'type' => $type,
            'content' => $form['content'],
            'hashtag' => $hashtag,
            'link' => $link,
            'reply_to' => $form['reply_to'],
            'priority' => 1,
            'client_ip' => $client_ip,
            'client_identification' => $client_identification,
            'user_id' => $this->user->id,
            'query_token' => $query_token,
            'delete_token' => $delete_token,
            'sync_to_bigplatform' => $sync_to_bigplatform,
        ];

        if ((int) $this->user->verified != 1) {
            $data['pending'] = 1;
        }
        else {
            $data = $this->checkLink($data);
            $data = $this->checkUserState($data);
            $data = $this->checkIdentification($data);
            $data = $this->checkIpBlacklist($data);

            if ($this->settingRepository->getValue('content_filter_enable') == 'on') {
                $data = $this->checkKeywordBlacklist($data, ($data['type'] == 4 ? $form['code'] : ''));
            }

            if ($this->settingRepository->getValue('content_filter_pendding_all') == 'on') {
                if (!isset($data['denied']) || $data['denied'] != 1)
                    $data['pending'] = 1;
            }
        }

        if ($id = $this->postRepository->createGetId($data))
        {
            if ($type == 3) {
                $this->postImageConfigRepository->create([
                    'post_id' => $id,
                    'config' => $form['image_config'],
                ]);
            }

            if ($type == 4) {
                $this->postCodeRepository->create([
                    'post_id' => $id,
                    'code' => $form['code'],
                ]);
            }

            if (array_key_exists('image_url', $form) && $form['image_url'] != '') {
                $this->postMediaRepository->create([
                    'post_id' => $id,
                    'type' => 'imgur',
                    'url' => $form['image_url'],
                ]);
            }

            return $this->successResponse('Ok.', ['key' => $key]);
        }
        else
            return $this->badRequestResponse('Post create failed.');
    }

    private function getClientIdentification($identification)
    {
        $result = $this->clientIdentificationRepository->getItemByIdentification($identification);
        return $result->id;
    }

    private function checkUserState($data)
    {
        if ($this->user->flagged) {
            if (!isset($data['denied']) || $data['denied'] != 1)
                $data['pending'] = 1;
        }

        if ($this->user->banned) {
            $data['denied'] = 1;
            if (isset($data['pending'])) $data['pending'] = 0;
        }

        return $data;
    }

    private function checkIdentification($data)
    {
        $result = $this->clientIdentificationRepository->getItem($data['client_identification']);

        if ((int) $result->forbidden == 1) {
            $data['denied'] = 1;
            if (isset($data['pending'])) $data['pending'] = 0;
        }

        return $data;
    }

    private function checkIpBlacklist($data)
    {
        $result = $this->ipBlacklistRepository->getItem($data['client_ip']);

        if (count($result)) {
            $data['pending'] = 1;
            if ((int) $result->forbidden == 1) {
                $data['denied'] = 1;
                if (isset($data['pending'])) $data['pending'] = 0;
            }
        }

        return $data;
    }

    private function checkLink($data)
    {
        $whitelist = listToArray($this->domainWhitelistRepository->getAll(), 'domain');
        $blacklist = listToArray($this->domainBlacklistRepository->getAll(), 'domain');

        $links = getUrlFromString($data['content']);

        if (!checkArrayContains($data['content'], $whitelist, true)) {
            if (!isset($data['denied']) || $data['denied'] != 1) {
                if ($data['type'] == 2 || count($links) > 0)
                    $data['pending'] = 1;
            }
        }

        if (checkArrayContains($data['content'], $blacklist, true)) {
            $data['denied'] = 1;
            if (isset($data['pending'])) $data['pending'] = 0;
        }

        return $data;
    }

    private function checkKeywordBlacklist($data, $code = '')
    {
        $result = $this->keywordBlacklistRepository->getAll();
        $keyword = listToArray($result, 'keyword', 'forbidden', 0);
        $keyword_forbidden = listToArray($result, 'keyword', 'forbidden', 1);

        if (checkArrayContains($data['content'], $keyword, true)) {
            if (!isset($data['denied']) || $data['denied'] != 1)
                $data['pending'] = 1;
        }

        if (checkArrayContains($data['content'], $keyword_forbidden, true))
        {
            $data['denied'] = 1;
            if (isset($data['pending'])) $data['pending'] = 0;
        }

        if ($data['type'] == 4)
        {
            if (checkArrayContains($code, $keyword, true)) {
                if (!isset($data['denied']) || $data['denied'] != 1)
                    $data['pending'] = 1;
            }

            if (checkArrayContains($code, $keyword_forbidden, true))
            {
                $data['denied'] = 1;
                if (isset($data['pending'])) $data['pending'] = 0;
            }
        }

        if ($this->settingRepository->getValue('content_filter_advanced_mode') == 'on')
        {
            $pinyin_content = $this->pinyinService->convert($data['content']);

            for ($i = 0; $i < count($keyword); $i++)
                $keyword[$i] = $this->pinyinService->convert($keyword[$i]);

            for ($i = 0; $i < count($keyword_forbidden); $i++)
                $keyword_forbidden[$i] = $this->pinyinService->convert($keyword_forbidden[$i]);

            if (checkArrayContains($pinyin_content, $keyword, true) && $data['denied'] != 1) {
                if (!isset($data['denied']) || $data['denied'] != 1)
                    $data['pending'] = 1;
            }

            if (checkArrayContains($pinyin_content, $keyword_forbidden, true)) {
                $data['denied'] = 1;
                if (isset($data['pending'])) $data['pending'] = 0;
            }
        }

        return $data;
    }
}
