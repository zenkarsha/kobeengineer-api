<?php

Route::get('/', 'DefaultController@deny');

# Autobot
Route::group(['prefix' => '/autobot'], function()
{
    Route::get('/poke/{id}', 'AutobotController@poke');
});
