<?php

namespace VJ;

class I18N
{

    public static function detectLanguage()
    {
        // TODO
    }

    public static function loadLanguage()
    {
        global $__LANG, $__LANG_DEFAULT;

        if (file_exists(APP_DIR.'i18n/'.$__LANG)) {
            require APP_DIR.'i18n/'.$__LANG.'.php';
        } else {
            require APP_DIR.'i18n/'.$__LANG_DEFAULT.'.php';
        }
    }

    public static function get()
    {
        $argv = func_get_args();
        if (count($argv) == 0) {
            return '';
        }
        
        $argv[0] = 'I18N_'.$argv[0];
        if (defined($argv[0])) {
            $argv[0] = constant($argv[0]);
        }
        if (count($argv) > 1) {
            $text = call_user_func_array('sprintf', $argv);
            return $text;
        } else {
            return $argv[0];
        }
    }

}