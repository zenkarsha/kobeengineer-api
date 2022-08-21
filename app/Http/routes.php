<?php

$middleware = [];

if (Config::get('app.debug')) {
    array_push($middleware, 'clearcache');
}

Route::group(['middleware' => $middleware], function()
{
    foreach (glob(__dir__ . '/../../routes/*.php') as $route)
        require $route;
});

