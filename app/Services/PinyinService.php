<?php

namespace App\Services;

use ChineseConverter;
use Pinyin;

class PinyinService extends Service
{
    public function convert($string)
    {
        $gb_string = ChineseConverter::big5_gb2312($string);
        $pinyin_string = Pinyin::permalink($gb_string);

        return $pinyin_string;
    }
}
