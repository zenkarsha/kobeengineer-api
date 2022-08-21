<?php

function postRequest($url, $params, $content_type = "application/x-www-form-urlencoded")
{
    $options = [
        'http' => [
            'header'  => "Content-type: " . $content_type . "\r\n",
            'method'  => 'POST',
            'content' => http_build_query($params)
        ]
    ];
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

function httpRequest($url)
{
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP'
            ]
        ]
    ];
    $context = stream_context_create($options);

    return file_get_contents($url, false, $context);
}
