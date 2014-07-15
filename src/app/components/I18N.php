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

}