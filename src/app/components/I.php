<?php

namespace VJ;

class I
{
    /**
     * 生成通用接口错误结构体
     *
     * @param $error_code
     *
     * @return array
     */
    public static function error($error_code)
    {

        if (defined('ERR_'.$error_code)) {
            $error_str = constant('ERR_'.$error_code);
        } else {
            $error_str = 'ERR_'.$error_code;
        }

        $argv = func_get_args();
        $text = gettext($error_str);

        if (count($argv) > 1) {
            $argv[0] = $text;
            $text    = call_user_func_array('sprintf', $argv);
        }

        return ['succeeded' => false, 'errorCode' => $error_code, 'errorMsg' => $text];

    }

    /**
     * 判断参数是否为一个通用接口错误结构体
     *
     * @param $i
     *
     * @return bool
     */
    public static function isError($i)
    {

        if (!is_array($i)) {
            return false;
        }

        if (isset($i['succeeded']) && $i['succeeded'] === false) {
            return true;
        } else {
            return false;
        }

    }

}