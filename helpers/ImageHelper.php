<?php

function base64ToImage($base64_string)
{
    $array = explode(',', $base64_string);
    $image = imagecreatefromstring(base64_decode($array[1]));

    return $image;
}

function imageUrlToBase64($url)
{
    return base64_encode(file_get_contents($url));
}

function clearBase64ImageType($base64_string)
{
    $array = explode(',', $base64_string);

    return $array[1];
}

function code2image($data)
{
    $url = 'http://instaco.de/api/highlight';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $image = curl_exec($curl);
    curl_close($curl);

    return $image;
}
