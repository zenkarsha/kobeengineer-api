<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIImageService;

/**
 * @resource Image
 */
class Image extends APICore
{
    protected $APIImageService;

    public function __construct(Request $request, APIImageService $APIImageService)
    {
        parent::__construct();

        $this->request = $request;
        $this->APIImageService = $APIImageService;
    }

    public function genPostImage($key)
    {
        if (array_key_exists('query_token', $this->request->all()))
            return $this->APIImageService->genPostImage($key, $this->request->get('query_token'));
        else
            return $this->abort();
    }

    public function genCodeImage(Requests\CodeImageCreateRequest $request)
    {
        if (array_key_exists('code', $this->request->all()))
            return $this->APIImageService->genCodeImage($this->request->all());
        else
            return $this->abort();
    }
}
