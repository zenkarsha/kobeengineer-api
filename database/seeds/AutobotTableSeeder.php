<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AutobotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'publisher',
                'access_token' => '',
                'session' => randString(),
                'job' => 'App\Jobs\Publisher@boot',
                'frequency' => 180,
            ],
            [
                'name' => 'analysiser',
                'access_token' => '',
                'session' => randString(),
                'job' => 'App\Jobs\Analysiser@boot',
                'frequency' => 60,
            ],
        ];

        foreach ($data as $item) {
            \DB::table('autobot')->insert($item);
        }
    }
}
