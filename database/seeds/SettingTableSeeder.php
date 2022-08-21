<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SettingTableSeeder extends Seeder
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
                'key' => 'facebook_page_name',
                'value' => '純靠北工程師',
                'type' => 'text',
                'label' => '專頁名稱',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_page_id',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook Page ID',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_page_token',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook Page token',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_app_id',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook APP ID',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_app_secret',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook APP secret',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_app_id_frontend',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook APP ID (前端用)',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'facebook_app_secret_frontend',
                'value' => '',
                'type' => 'text',
                'label' => 'Facebook APP secret (前端用)',
                'group' => '臉書設定',
                'custom_config' => '',
            ],
            [
                'key' => 'publisher_post_template',
                'value' => '#純靠北工程師[id]',
                'type' => 'textarea',
                'label' => '發文樣板',
                'group' => '發文設定',
                'custom_config' => '',
            ],
            [
                'key' => 'publisher_redirect_domains',
                'value' => '',
                'type' => 'textarea',
                'label' => '中繼轉址網站',
                'group' => '發文設定',
                'custom_config' => '',
            ],
            [
                'key' => 'content_filter_enable',
                'value' => '',
                'type' => 'slider',
                'label' => '啟用關鍵字過濾',
                'group' => '內容過濾',
                'custom_config' => '',
            ],
            [
                'key' => 'content_filter_advanced_mode',
                'value' => '',
                'type' => 'slider',
                'label' => '啟用同音字過濾模式',
                'group' => '內容過濾',
                'custom_config' => '',
            ],
            [
                'key' => 'content_filter_pendding_all',
                'value' => '',
                'type' => 'slider',
                'label' => '啟用全審核模式',
                'group' => '內容過濾',
                'custom_config' => '',
            ],
        ];

        foreach ($data as $item) {
            \DB::table('setting')->insert($item);
        }
    }
}
