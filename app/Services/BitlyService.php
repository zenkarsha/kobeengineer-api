<?php

namespace App\Services;

use App\Repositories\BitlyAccountRepository;

class BitlyService extends Service
{
    protected $repo;

    public function __construct(BitlyAccountRepository $repo)
    {
        $this->repo = $repo;

        $this->bitly_api = 'http://api.bit.ly/v3/';
        $this->bitly_oauth_api = 'https://api-ssl.bit.ly/v3/';
        $this->bitly_oauth_access_token = 'https://api-ssl.bit.ly/oauth/';
        $this->bitly_domains = ['bit.ly', 'j.mp'];
        $this->querier_domains = [
            'http://ehumzn3m43ukjp4gv8qaagbi41hlrbfj-tibet.rhcloud.com',
            'http://t8nqmluoihiq2rbxizuhrzcwz0qpyhzn-tibet.rhcloud.com',
            'http://wqgcubhlovzkf06i9jovyq-tibet.rhcloud.com',
        ];
        $this->max_usage = 5000;
    }

    public function create($long_url)
    {
        $this->initialUsage();
        $bitly_account = $this->repo->getLeastUsageItem();

        if (count($bitly_account))
        {
            if (!defined('bitlyKey'))
                define('bitlyKey', $bitly_account->bitly_key);
            if (!defined('bitlyLogin'))
                define('bitlyLogin', $bitly_account->bitly_login);
            if (!defined('bitly_clientid'))
                define('bitly_clientid', $bitly_account->bitly_clientid);
            if (!defined('bitly_secret'))
                define('bitly_secret', $bitly_account->bitly_secret);

            if (!defined('bitly_api'))
                define('bitly_api', $this->bitly_api);
            if (!defined('bitly_oauth_api'))
                define('bitly_oauth_api', $this->bitly_oauth_api);
            if (!defined('bitly_oauth_access_token'))
                define('bitly_oauth_access_token', $this->bitly_oauth_access_token);

            $response = $this->shorten($long_url, $bitly_account->bitly_access_token, $this->bitly_domains[array_rand($this->bitly_domains)]);

            if (isset($response['data']['url']))
            {
                $this->repo->increaseUsage($bitly_account->id);
                return $response;
            }
            else
                return $this->badRequestResponse('Bitly shorten url failed.');
        }
        else {
            \Log::error('Bitly shorten url failed, not enough resource.');
            return $this->badRequestResponse('Bitly shorten url failed, not enough resource.');
        }
    }

    public function shorten($long_url, $access_token, $domain = '')
    {
        $url = $this->bitly_oauth_api . 'shorten?access_token=' . $access_token . '&longUrl=' . urlencode($long_url);
        if ($domain != '') $url .= '&domain=' . $domain;

        try {
            $callback = json_decode(file_get_contents($url));
        } catch (\Exception $e) {
            $callback = json_decode(file_get_contents($this->querier_domains[array_rand($this->querier_domains)] . '?url=' . urlencode($url)));
        }

        if (isset($callback->{'data'}->{'hash'}))
        {
            $data = [
                'url' => $callback->{'data'}->{'url'},
                'url' => $callback->{'data'}->{'url'},
                'hash' => $callback->{'data'}->{'hash'},
                'global_hash' => $callback->{'data'}->{'global_hash'},
                'long_url' => $callback->{'data'}->{'long_url'},
                'new_hash' => $callback->{'data'}->{'new_hash'},
                'status_code' => $callback->status_code,
            ];

            return $this->successResponse('Ok.', $data);
        }
        else {
            return $this->badRequestResponse();
        }
    }

    private function initialUsage()
    {
        $month_begin = timestampToDatetime(strtotime('1-' . date('m') . '-' . date('Y')));
        $result = $this->repo->getExpiredItems($month_begin);

        if (count($result)) {
            foreach ($result as $item) {
                $this->repo->update($item->id, ['usage' => 0]);
            }
        }
    }
}

