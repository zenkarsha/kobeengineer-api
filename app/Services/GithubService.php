<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\PublisherQueueRepository;
use App\Repositories\PostMediaRepository;
use App\Services\PostService;
use View;
use GitHub;

class GithubService extends Service
{
    protected $settingRepository;
    protected $postRepository;
    protected $postCodeRepository;
    protected $postPublishedRepository;
    protected $publisherQueueRepository;
    protected $postMediaRepository;
    protected $postService;

    public function __construct(SettingRepository $settingRepository, PostRepository $postRepository, PostCodeRepository $postCodeRepository, PostPublishedRepository $postPublishedRepository, PublisherQueueRepository $publisherQueueRepository, PostMediaRepository $postMediaRepository, PostService $postService)
    {
        $this->settingRepository = $settingRepository;
        $this->postRepository = $postRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->postMediaRepository = $postMediaRepository;
        $this->postService = $postService;

        $this->facebook_page_name = $this->settingRepository->getValue('facebook_page_name');
        $this->organization = 'kobeengineer';
        $this->repo = 'init';
        // $this->repo = 'test';
        $this->branch = 'master';
        $this->folder_prefix = 'post-';
        $this->committer = [
            'name' => '',
            'email' => '',
        ];
    }

    public function publish($id, $true_id)
    {
        $post = $this->postRepository->getItem($id);
        $data = $this->handlePostData($post, $true_id);

        if ($data['type'] == 'image') {
            $this->putImage($data);
            sleep(1);
        }

        $response = $this->createPostReadme($data);
        # TODO: 之後視狀況開啟
        // if ($response['success'])
        // {
        //     if ($data['type'] == 'code') {
        //         $response = $this->createPostIssue($data);
        //     }
        // }
    }

    public function unpublish($id)
    {
        $post = $this->postRepository->getItem($id);

        $response = $this->deletePostReadme($post);
        if ($post->type == 3)
            $this->deleteImage($post);

        return $response;
    }

    public function unpublishIssue($post_id)
    {
        $post = $this->postRepository->getItem($post_id);
        $response = $this->closePostIssue($post);

        return $response;
    }

    private function getFileSha($post, $filename)
    {
        $url = 'https://api.github.com/repos/' . $this->organization . '/' . $this->repo . '/contents/' . $this->getParentFolderName($post->true_id) . $this->folder_prefix . $post->true_id . '/' . $filename;

        $result = httpRequest($url);
        $result = json_decode($result);

        return $result->sha;
    }

    private function createPostIssue($data)
    {
        $data['is_issue'] = true;

        if ($data['type'] == 'image')
            $data['image'] = $this->postService->getPostImageUrl($data['id']);

        try {
            $view = View::make('github.' . $data['type'], $data);
            $content = $view->render();

            $callback = GitHub::connection('other')->issue()->create($this->organization, $this->repo, [
                'title' => $data['issue_title'],
                'body' => $content,
            ]);

            if (isset($callback['number'])) {
                $this->postPublishedRepository->create([
                    'post_id' => $data['id'],
                    'type' => 'github_issue',
                    'success' => 1,
                    'url' => $callback['html_url'],
                    'pid' => $callback['number'],
                ]);
                $this->postRepository->increasePublished($data['id']);

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Create post issue on Github failed, callback: ' . json_encode($callback));
                $this->logPublishFailed($data['id'], 'github_issue');
                $response = $this->badRequestResponse('Create post issue on Github failed, callback: ' . json_encode($callback));
            }
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
                $this->logPublishFailed($data['id'], 'github_issue');
            $response = $this->badRequestResponse('Create post issue on Github failed.');
        }

        return $response;
    }

    private function closePostIssue($post)
    {
        $data = $this->postPublishedRepository->getItemsByPostIdAndType($post->id, 'github_issue');

        try {
            $callback = GitHub::connection('other')->issue()->update($this->organization, $this->repo, $data->pid, ['state' => 'closed']);

            if (isset($callback['state']) && $callback['state'] == 'closed')
            {
                $this->postPublishedRepository->deleteByPostIdAndType($post->id, 'github_issue');

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Close post issue on Github failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Close post issue on Github failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Close post issue on Github failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Close post issue on Github failed.');
        }

        return $response;
    }

    private function createPostReadme($data)
    {
        try {
            $path = $this->getParentFolderName($data['true_id']) . $this->folder_prefix . $data['true_id'] . '/README.md';
            $view = View::make('github.' . $data['type'], $data);
            $content = $view->render();
            $message = '🤖 ' . $data['issue_title'];

            $callback = GitHub::connection('other')->repo()->contents()->create($this->organization, $this->repo, $path, $content, $message, $this->branch, $this->committer);

            if (isset($callback['content']))
            {
                $this->postPublishedRepository->create([
                    'post_id' => $data['id'],
                    'type' => 'github',
                    'success' => 1,
                    'url' => $this->getGithubPostUrl($data['true_id']),
                ]);
                $this->postRepository->increasePublished($data['id']);

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Create post README on Github failed, error: ' . json_encode($callback));
                $this->logPublishFailed($data['id']);
                $response = $this->badRequestResponse('Create post README on Github failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Create post README on Github failed, error: ' . $e->getMessage());
            $this->logPublishFailed($data['id']);
            $response = $this->badRequestResponse('Create post README on Github failed.');
        }

        return $response;
    }

    private function deletePostReadme($post)
    {
        $sha = $this->getFileSha($post, 'README.md');
        $path = $this->getParentFolderName($post->true_id) . $this->folder_prefix . $post->true_id. '/README.md';
        $message = 'rm -rf';

        try {
            $callback = GitHub::connection('other')->repo()->contents()->rm($this->organization, $this->repo, $path, $message, $sha, $this->branch, $this->committer);

            if ($callback['content'] == null)
            {
                $this->postPublishedRepository->deleteByPostIdAndType($post->id, 'github');

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Delete post README on Github failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Delete post README on Github failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Delete post README on Github failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Delete post README on Github failed.');
        }

        return $response;
    }

    private function putImage($data)
    {
        try {
            $path = $this->getParentFolderName($data['true_id']) . $this->folder_prefix . $data['true_id'] . '/image' . $data['ext'];
            $content = file_get_contents($data['image']);
            $message = '🤖 ' . $data['issue_title'];

            $callback = GitHub::connection('other')->repo()->contents()->create($this->organization, $this->repo, $path, $content, $message, $this->branch, $this->committer);

            if (isset($callback['content']))
            {
                $github_media_url = $callback['content']['download_url'];
                $this->postMediaRepository->create([
                    'post_id' => $data['id'],
                    'type' => 'github',
                    'url' => $github_media_url,
                ]);

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Put post image file to Github failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Put post image file to Github failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Put post image file to Github failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Put post image file to Github failed.');
        }

        return $response;
    }

    private function deleteImage($post)
    {
        $url = $this->postMediaRepository->getItemValueByPostIdAndType($post->id, 'github');
        $ext = $this->getImageExt($url);
        $sha = $this->getFileSha($post, 'image' . $ext);
        $path = $this->getParentFolderName($post->true_id) . $this->folder_prefix . $post->true_id. '/image' . $ext;
        $message = 'rm -rf';

        try {
            $callback = GitHub::connection('other')->repo()->contents()->rm($this->organization, $this->repo, $path, $message, $sha, $this->branch, $this->committer);

            if ($callback['content'] == null)
            {
                $this->postMediaRepository->deleteByPostIdAndType($post->id, 'github');

                $response = $this->successResponse('Ok.', ['callback' => $callback]);
            }
            else {
                \Log::error('Delete post image on Github failed, error: ' . json_encode($callback));
                $response = $this->badRequestResponse('Delete post image on Github failed.');
            }
        }
        catch (\Exception $e) {
            \Log::error('Delete post image on Github failed, error: ' . $e->getMessage());
            $response = $this->badRequestResponse('Delete post image on Github failed.');
        }

        return $response;
    }

    private function getImageExt($url)
    {
        $size = getimagesize($url);
        return image_type_to_extension($size[2]);
    }

    public function getParentFolderName($true_id)
    {
        $base10_id = base_convert($true_id, 16, 10);
        $floor = floor($base10_id / 1000);

        return $this->folder_prefix . ($floor * 1000 + 1) . '-' . (($floor + 1) * 1000) . '/';
    }

    public function handlePostData($post, $true_id)
    {
        $data = [
            'id' => $post->id,
            'true_id' => $true_id,
            'issue_title' => $this->handleIssueTitle($post, $true_id),
            'facebook_page_name' => $this->facebook_page_name,
            'is_issue' => false,
        ];

        if ($post->hashtag != '')
            $data['hashtag'] = $post->hashtag;

        if ($post->reply_to != '')
            $data['reply_to'] = $post->reply_to;

        switch ((int) $post->type) {
            case 1:
                $data['type'] = 'text';
                $data['message'] = $post->content;
                break;
            case 2:
                $data['type'] = 'link';
                $data['message'] = $post->content;
                $data['link'] = $post->link;
                break;
            case 3:
                $data['type'] = 'image';
                $data['image'] = $this->postService->getPostImageUrl($post->id);
                $data['ext'] = $this->getImageExt($data['image']);
                break;
            case 4:
                $data['type'] = 'code';
                $data['message'] = $post->content;
                $data['code'] = $this->getPostCode($post->id);
                break;
        }

        return $data;
    }

    private function getPostCode($post_id)
    {
        $result = $this->postCodeRepository->getItemByPostId($post_id);

        return $result->code;
    }

    private function handleIssueTitle($post, $true_id)
    {
        $title = '#' . $this->facebook_page_name . $true_id;
        if (trim($post->content) != '') {
            $content = trim($post->content);
            $content = str_replace("\r", "", $content);
            $content = str_replace("\n", "", $content);
            if (mb_strlen($content, 'UTF-8') > 103)
                $title .= '：' . mb_substr($content, 0, 103) . '...';
            else
                $title .= '：' . $content;
        }

        return $title;
    }

    private function getGithubPostUrl($true_id)
    {
        return 'https://github.com/' . $this->organization . '/' . $this->repo . '/tree/' . $this->branch . '/' . $this->getParentFolderName($true_id) . $this->folder_prefix . $true_id;
    }

    private function logPublishFailed($id, $type = 'github')
    {
        return $this->postPublishedRepository->create([
            'post_id' => $id,
            'type' => $type,
            'success' => 0,
        ]);
    }
}
