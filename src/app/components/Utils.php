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

    /**
     * 计算字符串长度
     *
     * @param $str
     *
     * @return int
     */
    public static function len($str)
    {

        return mb_strlen($str, 'UTF-8');

    }

}