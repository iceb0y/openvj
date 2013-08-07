<?php

namespace VJ;

class Phalcon
{

    public static function initWhoops($di)
    {

        new \Whoops\Provider\Phalcon\WhoopsServiceProvider($di);

    }

    public static function initView($di)
    {

        global $_TEMPLATE_NAME;

        $di->set('view', function () use ($_TEMPLATE_NAME) {

            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../app/views/'.$_TEMPLATE_NAME.'/');
            $view->registerEngines(array('.volt' => function ($view, $di) {

                global $config;

                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions(array(
                    'compiledPath'      => ROOT_DIR.'runtime/compiled_templates/',
                    'compiledExtension' => '.compiled',
                    'compileAlways'     => (bool)$config->Template->compileAlways
                ));

                \VJ\View::extendVolt($volt, $view);

                return $volt;

            }));

            \VJ\View::extendView($view);

            return $view;
        });

    }

    public static function initSession($di)
    {

        global $config;

        session_name($config->Session->name);

        $di->setShared('session', function () use ($config) {

            $session = new \Phalcon\Session\Adapter\Redis(array(
                'path'     => $config->Session->redisPath,
                'uniqueId' => $config->Session->prefix
            ));

            $session->start();

            return $session;
        });

    }

}