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

    /**
     * 设置COOKIE
     *
     * @param $name
     * @param $value
     * @param $time
     *
     * @return bool
     */
    public static function setCookie($name, $value, $time = 0)
    {
        return setcookie($name, $value, $time, '/', '.'.ENV_HOST, false, true);
    }

    /**
     * 使COOKIE过期
     *
     * @param $name
     *
     * @return bool
     */
    public static function expireCookie($name)
    {
        return self::setCookie($name, '', time() - 36000);
    }

    /**
     * 判断是否是Ajax请求
     *
     * @return bool
     */
    public static function isAjax()
    {

        return (
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ||
            isset($_POST['ajax']) ||
            isset($_GET['ajax'])
        );

    }

}