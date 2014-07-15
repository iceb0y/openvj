<?php

namespace VJ\View;

class Basic extends \Phalcon\Mvc\View
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        $this->registerEngines(['.volt' => 'VJ\RenderEngine\Volt']);

    }

    public static function i18n()
    {
        $argv = func_get_args();
        $text = constant('I18N_'.$argv[0]);

        if (count($argv) > 1) {
            $argv[0] = $text;
            $text    = call_user_func_array('sprintf', $argv);
        }

        return $text;
    }

    public static function hasPriv($priv)
    {
        $acl = \Phalcon\DI::getDefault()->getShared('acl');

        return $acl->has(constant('PRIV_'.$priv));
    }

    public static function template($res, $version = true)
    {
        global $__CONFIG, $__TEMPLATE_NAME;

        $file = 'view/'.$__TEMPLATE_NAME.'/'.$res;
        $output = '//'.$__CONFIG->Misc->staticPrefix.'/'.$file;

        if ($version) {
            $fp = ROOT_DIR.'public/'.$file;
            if (file_exists($fp)) {
                $mtime = filemtime(ROOT_DIR.'public/'.$file);
            } else {
                $mtime = '0x14CC27';
            }
            $output .= '?v='.$mtime;
        }

        return $output;
    }

    public static function asset($res, $version = true)
    {
        global $__CONFIG;

        $file = 'static/'.$res;
        $output = '//'.$__CONFIG->Misc->staticPrefix.'/'.$file;

        if ($version) {
            $fp = ROOT_DIR.'public/'.$file;
            if (file_exists($fp)) {
                $mtime = filemtime(ROOT_DIR.'public/'.$file);
            } else {
                $mtime = '0x14CC27';
            }
            $output .= '?v='.$mtime;
        }

        return $output;
    }

    public static function page($url)
    {
        global $__CONFIG;
        
        return $__CONFIG->Misc->basePrefix.$url;
    }

    public static function processTime()
    {
        $elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        return sprintf('%f', $elapsed * 1000);
    }

}