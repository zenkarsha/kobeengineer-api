<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class Autobot extends Job implements SelfHandling
{
    public function __construct()
    {
        //
    }

    public function poke($job, $data)
    {
        try {
            sleep($data['delay']);
            file_get_contents($data['url']);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
