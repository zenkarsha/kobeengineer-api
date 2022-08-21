<?php

function getUrlFromString($string)
{
    $regex = urlRegex();
    preg_match_all($regex, $string, $matches);

    return $matches[0];
}

function getFirstUrlFromString($string)
{
    $matches = getUrlFromString($string);

    if (count($matches))
        return $matches[0];
    else
        return false;
}

function clearStringUrls($string)
{
    $regex = urlRegex();

    return preg_replace($regex, '', $string);
}

function randDomain($text)
{
    $result = $text;
    $text = str_replace("\r", "", $text);
    $domains = explode("\n", $text);

    if (count($domains))
        $result = $domains[array_rand($domains)];

    return $result;
}


function simpleUrl($url)
{
    $url = str_replace('http://', '', $url);
    $url = str_replace('https://', '', $url);

    return $url;
}
