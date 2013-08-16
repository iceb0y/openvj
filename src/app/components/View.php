<?php

namespace VJ;

class View
{

    public static function extendView($view)
    {
        //Assign global variables

        global $__CONFIG;

        $view->setVars(array(
            'BASE_PREFIX'      => $__CONFIG->Misc->basePrefix,
            'TITLE_SUFFIX'     => $__CONFIG->Misc->titleSuffix,
            'META_KEYWORD'     => $__CONFIG->Misc->metaKeyword,
            'META_DESC'        => $__CONFIG->Misc->metaDesc,
            'FOOTER_ICP'       => $__CONFIG->Misc->icp,
            'FOOTER_COPYRIGHT' => $__CONFIG->Misc->copyright,
            'FOOTER_VERSION'   => APP_NAME.' '.APP_VERSION,
        ));

    }

    public static function extendVolt($volt, $view)
    {

        global $__CONFIG;

        $volt->setOptions(array(
            'compiledPath'      => ROOT_DIR.'runtime/compiled_templates/',
            'compiledExtension' => '.compiled',
            'compileAlways'     => (bool)$__CONFIG->Template->compileAlways
        ));

        $compiler = $volt->getCompiler();
        $compiler->addFunction('view_static', 'VJ\View::view_static');
        $compiler->addFunction('view_processTime', 'VJ\View::view_processTime');
        $compiler->addFilter('i18n', 'VJ\View::view_i18n');
        $compiler->addFilter('html', 'VJ\Escaper::html');
        $compiler->addFilter('attr', 'VJ\Escaper::htmlAttr');
        $compiler->addFilter('uri', 'VJ\Escaper::uri');
        $compiler->addFilter('json', 'json_encode');

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
        $mtime  = filemtime(ROOT_DIR.'public/'.$file);

        if ($mtime) {
            $output .= '?v='.$mtime;
        }

        return $output;
    }

    public static function view_processTime()
    {
        $elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
        return sprintf('%f', $elapsed * 1000);
    }

}