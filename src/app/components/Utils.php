<?php

namespace VJ;


class Utils
{

    /**
     * 将字符按照指定长度切割
     *
     * @param     $str
     * @param int $len
     *
     * @return string
     */
    public static function strcut($str, $len = 200)
    {

        return mb_substr($str, 0, $len, 'UTF-8');

    }

}