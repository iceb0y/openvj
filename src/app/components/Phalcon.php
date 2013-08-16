<?php

namespace VJ;

class Phalcon
{

    /**
     * 初始化Whoops错误处理系统
     * modify from https://github.com/filp/whoops/blob/master/src/Whoops/Provider/Phalcon/WhoopsServiceProvider.php
     */
    public static function initWhoops()
    {

        $di = \Phalcon\DI::getDefault();

        // There's only ever going to be one error page...right?
        $di->setShared('whoops.error_page_handler', function () {
            return new \Whoops\Handler\PrettyPageHandler;
        });

        $json_handler = new \Whoops\Handler\JsonResponseHandler();
        $json_handler->onlyForAjaxRequests(true);

        // Retrieves info on the Phalcon environment and ships it off
        // to the PrettyPageHandler's data tables:
        // This works by adding a new handler to the stack that runs
        // before the error page, retrieving the shared page handler
        // instance, and working with it to add new data tables
        $phalcon_info_handler = function () use ($di) {
            try {
                $request = $di['request'];
            } catch (Exception $e) {
                // This error occurred too early in the application's life
                // and the request instance is not yet available.
                return;
            }

            // Request info:
            $di['whoops.error_page_handler']->addDataTable('Phalcon Application (Request)', array(
                'URI'          => $request->getScheme().'://'.$request->getServer('HTTP_HOST').$request->getServer('REQUEST_URI'),
                'Request URI'  => $request->getServer('REQUEST_URI'),
                'Path Info'    => $request->getServer('PATH_INFO'),
                'Query String' => $request->getServer('QUERY_STRING') ? : '<none>',
                'HTTP Method'  => $request->getMethod(),
                'Script Name'  => $request->getServer('SCRIPT_NAME'),
                //'Base Path'   => $request->getBasePath(),
                //'Base URL'    => $request->getBaseUrl(),
                'Scheme'       => $request->getScheme(),
                'Port'         => $request->getServer('SERVER_PORT'),
                'Host'         => $request->getServerName(),
            ));
        };

        $di->setShared('whoops', function () use ($di, $phalcon_info_handler, $json_handler) {
            $run = new \Whoops\Run;
            $run->pushHandler($di['whoops.error_page_handler']);
            $run->pushHandler($json_handler);
            $run->pushHandler($phalcon_info_handler);

            return $run;
        });

        $di['whoops']->register();

    }

    public static function initDatabase()
    {

        global $__CONFIG, $mongo;

        $mc = new \MongoClient($__CONFIG->Mongo->path, array(

            'db'               => $__CONFIG->Mongo->database,
            'username'         => $__CONFIG->Mongo->username,
            'password'         => $__CONFIG->Mongo->password,
            'connectTimeoutMS' => $__CONFIG->Mongo->timeout

        ));

        $mongo = $mc->selectDB($__CONFIG->Mongo->database);

        $di = \Phalcon\DI::getDefault();

        $di->set('mongo', function () use ($mongo) {

            return $mongo;

        }, true);

        $di->set('collectionManager', function () {

            return new \Phalcon\Mvc\Collection\Manager();

        }, true);

    }

    /**
     * 初始化模板引擎
     */
    public static function initView()
    {

        global $__TEMPLATE_NAME;

        $di = \Phalcon\DI::getDefault();

        $di->set('view', function () use ($__TEMPLATE_NAME) {

            $view = new \Phalcon\Mvc\View();

            $view->setViewsDir('../app/views/'.$__TEMPLATE_NAME.'/');
            $view->registerEngines(array('.volt' => function ($view, $di) {

                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
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

        global $__CONFIG, $__SESSION;

        $domain = '.'.ENV_HOST;
        $param  = session_get_cookie_params();

        session_set_cookie_params(
            $param['lifetime'], //lifetime
            '/', //path
            $domain, //domain
            false, //secure_only
            true //http_only
        );

        session_name($__CONFIG->Session->name);

        $di = \Phalcon\DI::getDefault();
        $di->setShared('session', function () use ($__CONFIG) {

            global $mongo;

            $session = new \Phalcon\Session\Adapter\Mongo(array(
                'collection' => $mongo->Session
            ));

            $session->start();

            return $session;
        });

        $__SESSION = $di->getSession();

    }

}