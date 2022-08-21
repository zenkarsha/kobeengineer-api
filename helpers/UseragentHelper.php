<?php

function getUserIP()
{
    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $ip[0];
    }
    return $ip;
}

function originalCheck($hosts)
{
    $original = '';
    if (isset($_SERVER['HTTP_REFERER'])) {
        $original = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    }

    if (in_array($original, $hosts))
        return true;
    else
        return false;
}
