<?php

function timestampToDatetime($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}

function currentTime()
{
    return timestampToDatetime(time());
}

function diffTimesInMinutes($a, $b)
{
    $a = strtotime($a);
    $b = strtotime($b);
    $interval = abs($b - $a);

    return round($interval / 60);
}
