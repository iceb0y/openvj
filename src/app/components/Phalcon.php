<?php

namespace VJ;

class Phalcon
{

    /**
     * 初始化Whoops错误处理系统
     */
    public static function initWhoops()
    {

        new \Whoops\Provider\Phalcon\WhoopsServiceProvider();

        $di = \Phalcon\DI::getDefault();
        $di['whoops']->pushHandler(new \Whoops\Handler\JsonResponseHandler());

    }

    /**
     * 初始化模板引擎
     */
    public static function initView()
    {

        global $_TEMPLATE_NAME;

        $di = \Phalcon\DI::getDefault();

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

    /**
     * 初始化Session
     */
    public static function initSession()
    {

        global $config, $SESSION;

        $domain = '.'.$config->Misc->host;
        $param  = session_get_cookie_params();

        session_set_cookie_params(
            $param['lifetime'], //lifetime
            '/', //path
            $domain, //domain
            false, //secure_only
            true //http_only
        );

        session_name($config->Session->name);

        $di = \Phalcon\DI::getDefault();
        $di->setShared('session', function () use ($config) {

            $session = new \Phalcon\Session\Adapter\Redis(array(
                'path'     => $config->Session->redisPath,
                'uniqueId' => $config->Session->prefix
            ));

            $session->start();

            return $session;
        });

        $SESSION = $di->getSession();

    }

}