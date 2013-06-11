<?php

namespace VJ;

class View
{

    public static function extendView($view)
    {
        //Assign global variables

        global $config;
        $view->setVars(array(
            'TITLE_SUFFIX'     => $config->Misc->titleSuffix,
            'META_KEYWORD'     => $config->Misc->metaKeyword,
            'META_DESC'        => $config->Misc->metaDesc,
            'FOOTER_ICP'       => $config->Misc->icp,
            'FOOTER_COPYRIGHT' => $config->Misc->copyright,
            'FOOTER_VERSION'   => APP_NAME.' '.APP_VERSION,
        ));
    }

    public static function extendVolt($volt, $view)
    {
        $compiler = $volt->getCompiler();

        //TODO: wait for BUG fixing
        $compiler->addFunction('view_static', function ($resolvedArgs, $exprArgs) {
            return 'VJ\View::view_static('.$resolvedArgs.')';
        });

        $compiler->addFunction('view_processTime', function ($resolvedArgs, $exprArgs) {
            return 'VJ\View::view_processTime('.$resolvedArgs.')';
        });

        $compiler->addFilter('i18n', function($resolvedArgs, $exprArgs) {
            return 'VJ\View::view_i18n('.$resolvedArgs.')';
        });
    }

    public static function view_i18n()
    {
        $argv = func_get_args();
        $text = gettext($argv[0]);

        if (count($argv) > 1)
        {
            array_shift($argv);
            $text = call_user_func_array('sprintf', $argv);
        }

        return $text;
    }

    public static function view_static($res, $static = false)
    {
        global $config, $_TEMPLATE_NAME;

        if ($static)
            $file = 'static/'.$res;
        else
            $file = 'view/'.$_TEMPLATE_NAME.'/'.$res;

        $output = '//'.$config->Misc->cdnHost.'/'.$file;
        $mtime  = filemtime(ROOT_DIR.'public/'.$file);

        if ($mtime)
            $output .= '?v='.$mtime;

        return $output;
    }

    public static function view_processTime()
    {
        global $_START_TIME;
        $_START_TIME += microtime(true);

        return sprintf('%f', $_START_TIME * 1000);
    }
}