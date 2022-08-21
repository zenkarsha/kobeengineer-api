<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use ChineseConverter;
use Pinyin;

use App\Services\FacebookService;
// use App\Services\TwitterService;
// use App\Services\GithubService;

use Jieba;
use Finalseg;
ini_set('memory_limit', '2048M'); # for Jieba

// use GitHub;
use Queue;
use Facebook;

use App\Services\AnalysisService;
use App\Repositories\PostRepository;
use App\Services\UsersNamePoolService;
use App\Repositories\SettingRepository;
use App\Repositories\PublisherQueueRepository;
use App\Services\BitlyService;

class TestController extends Controller
{
    protected $facebookService;
    // protected $twitterService;
    // protected $githubService;
    protected $analysisService;
    protected $postRepository;
    protected $settingRepository;
    protected $publisherQueueRepository;
    protected $bitlyService;

    // public function __construct(FacebookService $facebookService, TwitterService $twitterService, GithubService $githubService, AnalysisService $analysisService, PostRepository $postRepository, UsersNamePoolService $usersNamePoolService, SettingRepository $settingRepository, PublisherQueueRepository $publisherQueueRepository, BitlyService $bitlyService)
    public function __construct(FacebookService $facebookService, AnalysisService $analysisService, PostRepository $postRepository, UsersNamePoolService $usersNamePoolService, SettingRepository $settingRepository, PublisherQueueRepository $publisherQueueRepository, BitlyService $bitlyService)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->facebookService = $facebookService;
        // $this->twitterService = $twitterService;
        // $this->githubService = $githubService;
        $this->analysisService = $analysisService;
        $this->usersNamePoolService = $usersNamePoolService;
        $this->settingRepository = $settingRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->bitlyService = $bitlyService;

        $this->time_start = microtime(true);
    }

    public function home(Request $request)
    {
        // $this->testHackerrankAPI();
        // $this->togglePublisher();
        // $this->testFacebookTextPost();
        // $this->testFacebookImagePost();
        // $this->testUpdateFacebookPost();
        // $this->testTwitterPost();
        // $this->testBitly();

        // echo '<h1>' . (microtime(true) - $this->time_start) . '</h1>';
    }

    private function testHackerrankAPI()
    {
        $input = '1';
        $code = 'print 1';
        $url = 'http://api.hackerrank.com/checker/submission.json';
        $key = '';

        $query = array();

        $query['source'] = urlencode($code);
        $query['testcases'] = urlencode(json_encode(array($input)));
        $query['lang'] = 5;
        $query['api_key'] = urlencode($key);

        $q = array();
        foreach ($query as $k => $v) {
            $q[] = "$k=$v";
        }
        $q = implode("&", $q);
        echo $q;

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $q
            ]
        ];

        try {
            $context  = stream_context_create($options);
            $callback = file_get_contents($url, false, $context);
            dump(json_decode($callback));
        } catch (\Exception $e) {
            dump($e);
        }
    }

    private function testBitly()
    {
        $url = 'https://api.kobeengineer.io/v1/post/image/3gTWj9Wws?query_token=';
        $result = $this->bitlyService->create($url);
        dump($result);
    }

    private function togglePublisher()
    {
        Queue::push('App\Jobs\Publisher@boot', false);
        echo 'done';
    }

    private function testJieba($string)
    {
        Jieba::init(['mode'=>'default', 'dict'=>'big']);
        Finalseg::init();
        $result = Jieba::cut($string);
        dump($result);
    }

    private function testPinyin($string)
    {
        $gb_string = ChineseConverter::big5_gb2312($string);
        $pinyin_string = Pinyin::permalink($gb_string);
        echo $pinyin_string;
    }

    private function testFacebookTextPost()
    {
        $data = [
            'message' => 'facebook publish test ' . randString(10),
        ];

        $this->facebook_page_token = $this->settingRepository->getValue('facebook_page_token');
        $this->facebook_app_id = $this->settingRepository->getValue('facebook_app_id');
        $this->facebook_app_secret = $this->settingRepository->getValue('facebook_app_secret');
        $this->facebook_config = [
            'http_client_handler' => 'stream',
            'app_id' => $this->facebook_app_id,
            'app_secret' => $this->facebook_app_secret,
            'default_graph_version' => 'v2.10',
            'default_access_token' => $this->facebook_page_token,
        ];

        $fb = new Facebook($this->facebook_config);
        $callback = $fb->post('/me/feed/', $data);
        $callback = $callback->getDecodedBody();

        dump($callback);
    }

    private function testFacebookImagePost()
    {
        $data = [
            'caption' => 'facebook publish image test ' . randString(10),
            'url' => 'https://miro.medium.com/max/1024/1*3zGii76MzWScn-cxRkgFVQ.jpeg',
        ];

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
        $fb = new Facebook($this->facebook_config);
        $callback = $fb->post('/'.$this->facebook_page_id.'/photos/', $data);
        $callback = $callback->getDecodedBody();
        dump($callback);
    }

    private function testUpdateFacebookPost()
    {
        $data = [
            'message' => 'test link: bit.ly/gginit',
        ];

        $this->facebook_page_id = $this->settingRepository->getValue('facebook_page_id');
        $this->facebook_page_token = $this->settingRepository->getValue('facebook_page_token');
        $this->facebook_app_id = $this->settingRepository->getValue('facebook_app_id');
        $this->facebook_app_secret = $this->settingRepository->getValue('facebook_app_secret');
        $this->facebook_config = [
            'http_client_handler' => 'stream',
            'app_id' => $this->facebook_app_id,
            'app_secret' => $this->facebook_app_secret,
            'default_graph_version' => 'v2.10',
            'default_access_token' => $this->facebook_page_token,
        ];
        $fb = new Facebook($this->facebook_config);
        $callback = $fb->post('/1526821024030339_1532612416784533', $data);
        $callback = $callback->getDecodedBody();
        dump($callback);
    }

    // private function testTwitterPost()
    // {
    //     $data = [
    //         'type' => 'image',
    //         'message' => 'Hello World! ' . randString(10),
    //         'image' => 'http://i.imgur.com/uRcCwea.png',
    //     ];
    //     $response = $this->twitterService->publish($data);
    //     dump($response);
    // }
}
