<?php

Route::group(['prefix' => '/' . env('DASHBOARD_PATH'), 'middleware' => 'auth.very_basic'], function ()
{
    # Home
    Route::get('/', 'Dashboard\DashboardController@home');

    # Post
    Route::group(['prefix' => '/post'], function()
    {
        Route::get('/', 'Dashboard\PostViewController@overview');
        Route::get('/overview', 'Dashboard\PostViewController@overview');
        Route::get('/overview/all', 'Dashboard\PostViewController@overviewAll');
        Route::get('/pending', 'Dashboard\PostViewController@pending');
        Route::get('/search', 'Dashboard\PostViewController@search');
        Route::get('/create', 'Dashboard\PostViewController@create');

        Route::post('/create', 'Dashboard\PostController@create');

        Route::group(['prefix' => '/old'], function()
        {
            Route::get('/restore', 'Dashboard\PostViewController@restore');

            Route::post('/allow/{id}', 'Dashboard\OldPostController@allow');
            Route::post('/skip/{id}', 'Dashboard\OldPostController@skip');
            Route::post('/deny/{id}', 'Dashboard\OldPostController@deny');
        });

        Route::group(['prefix' => '/action'], function()
        {
            Route::post('/allow/{id}', 'Dashboard\PostActionController@allow');
            Route::post('/deny/{id}', 'Dashboard\PostActionController@deny');
            Route::post('/delete/{id}', 'Dashboard\PostActionController@delete');
            Route::post('/cancel_queuing/{id}', 'Dashboard\PostActionController@cancelQueuing');
            Route::post('/set_priority/{id}', 'Dashboard\PostActionController@setPriority');
            Route::post('/unpublish/{id}', 'Dashboard\PostActionController@unpublish');
            Route::post('/republish/{id}', 'Dashboard\PostActionController@republish');

            Route::post('/ban/{id}', 'Dashboard\PostActionController@ban');
            Route::post('/flag_user/{id}', 'Dashboard\PostActionController@flagUser');
            Route::post('/ban_user/{id}', 'Dashboard\PostActionController@banUser');
            Route::post('/ban_ip/{id}', 'Dashboard\PostActionController@banIp');
            Route::post('/ban_ip_forbidden/{id}', 'Dashboard\PostActionController@banIpForbidden');
            Route::post('/ban_client_identification/{id}', 'Dashboard\PostActionController@banClientIdentification');

            Route::post('/unban/{id}', 'Dashboard\PostActionController@unban');
            Route::post('/unflag_user/{id}', 'Dashboard\PostActionController@unflagUser');
            Route::post('/unban_user/{id}', 'Dashboard\PostActionController@unbanUser');
            Route::post('/unban_ip/{id}', 'Dashboard\PostActionController@unbanIp');
            Route::post('/unban_client_identification/{id}', 'Dashboard\PostActionController@unbanClientIdentification');
        });

        Route::group(['prefix' => '/queue'], function()
        {
            Route::get('/', 'Dashboard\PostViewController@queue');
            Route::get('/overview', 'Dashboard\PostViewController@queue');
            Route::post('/delete/{id}', 'Dashboard\PostController@queueDelete');
        });
    });

    # Autobot
    Route::group(['prefix' => '/autobot'], function()
    {
        Route::get('/', 'Dashboard\AutobotViewController@overview');
        Route::get('/overview', 'Dashboard\AutobotViewController@overview');
        Route::get('/create', 'Dashboard\AutobotViewController@create');
        Route::get('/edit/{id}', 'Dashboard\AutobotViewController@edit');

        Route::post('/create', 'Dashboard\AutobotController@create');
        Route::post('/update', 'Dashboard\AutobotController@update');
        Route::post('/delete/{id}', 'Dashboard\AutobotController@delete');
        Route::post('/boot/{id}', 'Dashboard\AutobotController@boot');
        Route::post('/reboot/{id}', 'Dashboard\AutobotController@reboot');
        Route::post('/refresh/{id}', 'Dashboard\AutobotController@refresh');
    });

    # User
    Route::group(['prefix' => '/user'], function()
    {
        Route::get('/', 'Dashboard\UserViewController@overview');
        Route::get('/overview', 'Dashboard\UserViewController@overview');
        Route::get('/search', 'Dashboard\UserViewController@search');

        Route::post('/flag/{id}', 'Dashboard\UserController@flag');
        Route::post('/ban/{id}', 'Dashboard\UserController@ban');
    });

    # Setting
    Route::group(['prefix' => '/setting'], function()
    {
        Route::get('/', 'Dashboard\SettingViewController@home');
        Route::get('/home', 'Dashboard\SettingViewController@home');
        Route::get('/advanced-mode', 'Dashboard\SettingViewController@advancedMode');
        Route::get('/create', 'Dashboard\SettingViewController@create');
        Route::get('/edit/{id}', 'Dashboard\SettingViewController@edit');

        Route::post('/create', 'Dashboard\SettingController@create');
        Route::post('/save', 'Dashboard\SettingController@save');
        Route::post('/update', 'Dashboard\SettingController@update');
        Route::post('/delete/{id}', 'Dashboard\SettingController@delete');
        Route::post('/get-page-token', 'Dashboard\SettingController@getPageToken');

        Route::group(['prefix' => '/keyword-blacklist'], function()
        {
            Route::get('/', 'Dashboard\KeywordBlacklistViewController@home');
            Route::post('/create', 'Dashboard\KeywordBlacklistController@create');
            Route::post('/delete/{id}', 'Dashboard\KeywordBlacklistController@delete');
            Route::post('/update-forbidden/{id}', 'Dashboard\KeywordBlacklistController@updateForbidden');
        });

        Route::group(['prefix' => '/ip-blacklist'], function()
        {
            Route::get('/', 'Dashboard\IpBlacklistViewController@home');
            Route::post('/create', 'Dashboard\IpBlacklistController@create');
            Route::post('/delete/{id}', 'Dashboard\IpBlacklistController@delete');
            Route::post('/update-forbidden/{id}', 'Dashboard\IpBlacklistController@updateForbidden');
        });

        Route::group(['prefix' => '/client-identification'], function()
        {
            Route::get('/', 'Dashboard\ClientIdentificationViewController@home');
            Route::post('/update-forbidden/{id}', 'Dashboard\ClientIdentificationController@updateForbidden');
        });

        Route::group(['prefix' => '/domain-whitelist'], function()
        {
            Route::get('/', 'Dashboard\DomainWhitelistViewController@home');
            Route::post('/create', 'Dashboard\DomainWhitelistController@create');
            Route::post('/delete/{id}', 'Dashboard\DomainWhitelistController@delete');
        });

        Route::group(['prefix' => '/domain-blacklist'], function()
        {
            Route::get('/', 'Dashboard\DomainBlacklistViewController@home');
            Route::post('/create', 'Dashboard\DomainBlacklistController@create');
            Route::post('/delete/{id}', 'Dashboard\DomainBlacklistController@delete');
        });

        Route::group(['prefix' => '/bitly-account'], function()
        {
            Route::get('/', 'Dashboard\BitlyAccountViewController@home');
            Route::post('/create', 'Dashboard\BitlyAccountController@create');
            Route::post('/delete/{id}', 'Dashboard\BitlyAccountController@delete');
        });
    });

    # Test
    Route::group(['prefix' => '/test'], function()
    {
        Route::get('/', 'TestController@home');
    });

    # System Log
    Route::get('log', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});
