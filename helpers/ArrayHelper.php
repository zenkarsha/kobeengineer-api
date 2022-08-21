<?php

function searchArrayByKeyValue($array, $target_key, $target_value)
{
   foreach ($array as $item)
       if ($item[$target_key] === $target_value)
           return $item;

   return null;
}

function searchObjectByKeyValue($object, $key, $value)
{
    foreach ($object as $item)
        if ($item->{$key} == $value)
            return $item;

    return null;
}

function mergeArray($default_array, $custom_array)
{
    // return array_unique(array_merge($default_array, $custom_array), SORT_REGULAR);

    $array = $custom_array;
    foreach ($default_array as $key => $value) {
        if (!array_key_exists($key, $array)) {
            $array[$key] = $value;
        }
    }

    return $array;
}

function arrayKeyExists($key, $array)
{
    if (array_key_exists($key, $array) && $array[$key])
        return true;

    return false;
}

function objectToArray($object)
{
    return json_decode(json_encode($object), TRUE);
}

function jsonBeautify($string)
{
    return json_encode(json_decode($string), JSON_PRETTY_PRINT);
}

function checkArrayContains($string, $array, $lowercase = false)
{
    if ($lowercase) $string = strtolower($string);

    foreach($array as $a) {
        if ($lowercase) $a = strtolower($a);
        if (stripos($string, $a) !== false)
            return true;
    }

    return false;
}

function listToArray($object, $keyname, $where = null, $where_value = null)
{
    $array = [];
    foreach ($object as $item) {
        if ($where != null) {
            if ($item->$where != $where_value)
                continue;
        }
        array_push($array, $item->$keyname);
    }

    return $array;
}
