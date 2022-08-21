<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\SettingRepository;
use App\Repositories\PostPublishedRepository;
use Carbon\Carbon;
use Queue;

class FacebookQuerierService extends Service
{
    protected $postRepository;
    protected $settingRepository;
    protected $postPublishedRepository;

    public function __construct(PostRepository $postRepository, SettingRepository $settingRepository, PostPublishedRepository $postPublishedRepository)
    {
        $this->postRepository = $postRepository;
        $this->settingRepository = $settingRepository;
        $this->postPublishedRepository = $postPublishedRepository;

        $this->debug = true;
        $this->debug = false;
    }

    public function handle()
    {
        $time = time() - 60 * 60 * 24 * 30;
        $datetime = timestampToDatetime($time);
        $result = $this->postRepository->getPublishedPostsByDatetime($datetime);

        foreach ($result as $post) {
            $this->query($post->id);
            sleep(1);
            // Queue::push('App\Jobs\FacebookQuerier@query', $post->id);
        }
    }

    public function query($post_id)
    {
        $published_data = $this->postPublishedRepository->getItemsByPostIdAndType($post_id, 'facebook');
        $access_token = $this->settingRepository->getValue('facebook_page_token');

        if (count($published_data))
        {
            $data = [
                'fb_likes' => 0,
                'fb_comments' => 0,
                'fb_shares' => 0,
                'fb_social_total' => 0,
            ];

            # query likes and comments
            $url = 'https://graph.facebook.com/' . $published_data->pid . '/?fields=comments.limit(0).summary(true),likes.limit(0).summary(true)&access_token=' . $access_token;
            try {
                $result = json_decode(file_get_contents($url));
                if (isset($result->likes->summary->total_count)) {
                    $data['fb_likes'] = (int) $result->likes->summary->total_count;
                    $data['fb_comments'] = (int) $result->comments->summary->total_count;
                }
                else {
                    if ($this->debug) \Log::error('FacebookQuerier: #' . $post_id . ' query failed, callback: ' . json_encode($result));
                }
            } catch (\Exception $e) {
                if ($this->debug)  \Log::error('FacebookQuerier: #' . $post_id . ' query failed, error: ' . $e->getMessage());
            }

            # query shares
            $url_shares = 'https://graph.facebook.com/' . $published_data->pid . '/?fields=shares&access_token=' . $access_token;
            try {
                $result = json_decode(file_get_contents($url_shares));
                if (isset($result->shares->count)) {
                    $data['fb_shares'] = (int) $result->shares->count;
                }
                else {
                    if ($this->debug) \Log::error('FacebookQuerier: #' . $post_id . ' query failed(shares), callback: ' . json_encode($result));
                }
            } catch (\Exception $e) {
                if ($this->debug) \Log::error('FacebookQuerier: #' . $post_id . ' query failed(shares), error: ' . $e->getMessage());
            }

            $data['fb_social_total'] = $this->countTotal($data);

            $this->postRepository->update($post_id, $data);

            return $data;
        }
        else {
            return false;
        }
    }

    private function countTotal($data)
    {
        return $data['fb_likes'] + $data['fb_comments'] + $data['fb_shares'];
    }
}
