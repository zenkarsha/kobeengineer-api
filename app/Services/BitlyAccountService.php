<?php

namespace App\Services;

use App\Repositories\BitlyAccountRepository;

class BitlyAccountService extends Service
{
    protected $repo;

    public function __construct(BitlyAccountRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($form)
    {
        $result = $this->repo->create([
            'bitly_key' => $form['bitly_key'],
            'bitly_login' => $form['bitly_login'],
            'bitly_clientid' => $form['bitly_clientid'],
            'bitly_secret' => $form['bitly_secret'],
            'bitly_access_token' => $form['bitly_access_token'],
            'usage' => $form['usage'],
        ]);

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Create failed.');
    }
}

