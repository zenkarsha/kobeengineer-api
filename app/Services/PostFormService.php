<?php

namespace App\Services;

class PostFormService extends Service
{
    public function getPostType($form)
    {
        switch ($form['type']) {
          case 'image': return 3;
          case 'code': return 4;
          case 'text':
          default:
              if (getFirstUrlFromString($form['content']) == false)
                  return 1;
              else
                  return 2;
        }
    }

    public function handleHashtag($string)
    {
        preg_match_all("/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u", $string, $matches);

        return implode(' ', $matches[0]);
    }

    public function getPostLink($form)
    {
        $result = getFirstUrlFromString($form['content']);

        if ($result == false)
            return '';
        else
            return $result;
    }

    public function getCheckboxValue($field_key, $array, $boolean = false)
    {
        $value = (array_key_exists($field_key, $array) && $array[$field_key] == 'on') ? 1 : 0;
        if ($boolean)
            return (boolean) $value;
        return $value;
    }
}
