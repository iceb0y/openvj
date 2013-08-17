<?php

namespace VJ;

class View extends \Phalcon\Mvc\View
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        global $__TEMPLATE_NAME, $__CONFIG;

        $this->setViewsDir('../app/views/'.$__TEMPLATE_NAME.'/');
        $this->registerEngines(['.volt' => 'VJ\Volt']);

        //Assign global variables

        $this->setVars([
            'BASE_PREFIX'      => $__CONFIG->Misc->basePrefix,
            'TITLE_SUFFIX'     => $__CONFIG->Misc->titleSuffix,
            'META_KEYWORD'     => $__CONFIG->Misc->metaKeyword,
            'META_DESC'        => $__CONFIG->Misc->metaDesc,
            'FOOTER_ICP'       => $__CONFIG->Misc->icp,
            'FOOTER_COPYRIGHT' => $__CONFIG->Misc->copyright,
            'FOOTER_VERSION'   => APP_NAME.' '.APP_VERSION,
        ]);

    }

    public static function view_i18n()
    {
        $argv = func_get_args();
        $text = gettext($argv[0]);

        if (count($argv) > 1) {
            $argv[0] = $text;
            $text    = call_user_func_array('sprintf', $argv);
        }

        return $text;
    }

    public static function view_static($res, $static = false)
    {
        global $__CONFIG, $__TEMPLATE_NAME;

        if ($static) {
            $file = 'static/'.$res;
        } else {
            $file = 'view/'.$__TEMPLATE_NAME.'/'.$res;
        }

        $output = '//'.$__CONFIG->Misc->staticPrefix.'/'.$file;

        $fp = ROOT_DIR.'public/'.$file;

        if (file_exists($fp)) {
            $mtime = filemtime(ROOT_DIR.'public/'.$file);
        } else {
            $mtime = '0';
        }

        $output .= '?v='.$mtime;

        return $output;
    }

    public static function view_processTime()
    {
        $elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        return sprintf('%f', $elapsed * 1000);
    }

}