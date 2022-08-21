<?php

Route::group(['prefix' => '/v1'], function()
{
    Route::group(['prefix' => '/create', 'middleware' => 'jwt.auth'], function() {
        Route::post('/', 'API\v1\Post@createPost');
        Route::post('/code-image', 'API\v1\Image@genCodeImage');
    });

    Route::group(['prefix' => '/post'], function() {
        Route::get('/', 'API\v1\Post@getPost');
        Route::get('/comments', 'API\v1\Post@getPostComments');
        Route::get('/image/{key}', 'API\v1\Image@genPostImage');
    });

    Route::group(['prefix' => '/post', 'middleware' => 'jwt.auth'], function() {
        Route::post('/like/{id}', 'API\v1\Reaction@likePost');
        Route::post('/comment/{id}', 'API\v1\Reaction@commentPost');
        Route::post('/comment/like/{id}', 'API\v1\Reaction@likeComment');
    });

    Route::group(['prefix' => '/report', 'middleware' => 'jwt.auth'], function() {
        Route::post('/{id}', 'API\v1\Report@reportPost');
    });

    Route::group(['prefix' => '/feed'], function() {
        Route::get('/', 'API\v1\Feed@home');
        Route::get('/author', 'API\v1\Feed@authorFeed');
        Route::get('/authors', 'API\v1\Feed@authors');
        Route::get('/recently', 'API\v1\Feed@recently');
    });

    Route::group(['prefix' => '/me', 'middleware' => 'jwt.auth'], function() {
        Route::get('/', 'API\v1\User@me');
        Route::get('/posts', 'API\v1\User@getUserPosts');

        Route::post('/post/delete', 'API\v1\User@deleteUserPost');
        Route::post('/setting', 'API\v1\User@updateSetting');
    });

    Route::group(['prefix' => '/client'], function() {
        Route::post('/', 'API\v1\Client@create');
        Route::post('/check', 'API\v1\Client@check');
    });
});
