<?php

namespace VJ;

class I
{

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

        return array('succeeded' => false, 'errorCode' => $error_code, 'errorMsg' => $text);

    }

}