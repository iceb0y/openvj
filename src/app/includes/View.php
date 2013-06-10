<?php

namespace VJ;

class View
{

    public static function extendView($view)
    {
        //Assign global variables

        global $config;
        $view->TITLE_SUFFIX = $config->Misc->titleSuffix;
    }

    public static function extendVolt($volt, $view)
    {
        $compiler = $volt->getCompiler();

        //TODO: wait for BUG fixing
        $compiler->addFunction('view_static', function($resolvedArgs, $exprArgs) {
            return 'VJ\View::view_static('.$resolvedArgs.')';
        });

        $compiler->addFunction('view_processTime', function($resolvedArgs, $exprArgs) {
            return 'VJ\View::view_processTime('.$resolvedArgs.')';
        });
    }

    public static function view_static($res, $static = false)
    {
        global $config, $_TEMPLATE_NAME;

        if ($static)
            $file = 'static/'.$res;
        else
            $file = 'view/'.$_TEMPLATE_NAME.'/'.$res;
        
        $output = '//'.$config->Misc->CDN.'/'.$file;
        $mtime = filemtime(ROOT_DIR.'public/'.$file);
        
        if ($mtime)
            $output.='?v='.$mtime;
        
        return $output;
    }

    public static function view_processTime()
    {
        global $_START_TIME;
        $_START_TIME += microtime(true);
        return sprintf('%f', $_START_TIME * 1000);
    }
}