<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Services\FacebookQuerierService;

class FacebookQuerier extends Job implements SelfHandling
{
    protected $facebookQuerierService;

    public function __construct(FacebookQuerierService $facebookQuerierService)
    {
        $this->facebookQuerierService = $facebookQuerierService;
    }

    public function boot($job)
    {
        $this->facebookQuerierService->handle();
    }

    public function query($job, $post_id)
    {
        $this->facebookQuerierService->query($post_id);
    }
}
