<?php

function randString($length = 10)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ($i = 0; $i < $length; $i++) {
        @$string .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $string;
}

function base62($num)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $r = $num % 62;
    $res = $chars[$r];
    $q = floor($num / 62);
    while ($q) {
        $r = $q % 62;
        $q = floor($q / 62);
        $res = $chars[$r] . $res;
    }
    return $res;
}

function utf8StringSplit($string, $split_length = 1)
{
    if (!preg_match('/^[0-9]+$/', $split_length) || $split_length < 1) return false;

    $length = mb_strlen($string, 'UTF-8');
    if ($length <= $split_length) return array($string);

    $regex = '/.{' . $split_length . '}|[^\x00]{1,' . $split_length . '}$/us';
    preg_match_all($regex, $string, $array);

    return $array[0];
}

function clearStringSymbols($string)
{
    $chars = [
        '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*',
        '+', ', ', '-', '.', '/', ':', ';', '<', '=', '>',
        '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|',
        '}', '~', '；', '﹔', '︰', '﹕', '：', '，', '﹐', '、',
        '．', '﹒', '˙', '·', '。', '？', '！', '～', '‥', '‧',
        '′', '〃', '〝', '〞', '‵', '‘', '’', '『', '』', '「',
        '」', '“', '”', '…', '❞', '❝', '﹁', '﹂', '﹃', '﹄',
        '〔', '〕', '【', '】', '﹝', '﹞', '〈', '〉', '﹙', '﹚',
        '《', '》', '（', '）', '｛', '｝', '﹛', '﹜', '︵', '︶',
        '︷', '︸', '︹', '︺', '︻', '︼', '︽', '︾', '︿', '﹀',
        '＜', '＞', '∩', '∪',
    ];
    $string = preg_replace("/[[:punct:]\s]/",'',$string);
    $string = str_replace($chars, '', $string);

    return $string;
}

function sliceStringToArray($string, $length, $clear_symbols = true)
{
    if ($clear_symbols)
        $string = clearStringSymbols($string);

    $words = utf8StringSplit($string);
    $total = count($words);

    $array = [];
    for ($i = 0; $i < $total; $i++) {
        if ($i < $total - $length + 1) {
            $word = implode('', array_slice($words, $i, $length));
            array_push($array, $word);
        }
    }

    return $array;
}

function startWith($start_string, $search_string)
{
    return strtolower(substr($search_string, 0, strlen($start_string))) == strtolower($start_string) ? true : false;
}

function textToSummary($text, $length = 50)
{
    $text = str_replace("\n", "", $text);
    $text = str_replace("\r", "", $text);
    if (strlen($text) > $length) $text = mb_substr($text, 0, $length, 'UTF-8') . '...';
    $text = htmlentities($text);

    return $text;
}
