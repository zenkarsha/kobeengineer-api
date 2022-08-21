<?php

function uploadToImgur($image, $ext, $description = '')
{
    $image = getOutputBufferingImage($image, $ext);
    $client_id = \Config::get('generator.imgur_client_id');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Client-ID ' . $client_id]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'image' => base64_encode($image),
        'description' => $description,
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result);
}
