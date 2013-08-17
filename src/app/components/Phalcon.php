<?php

namespace VJ;

class Phalcon
{

    public static function initDatabase()
    {

        global $__CONFIG;

        $di = \Phalcon\DI::getDefault();

        $di->set('mongo', function () use ($__CONFIG) {

            $mc = new \MongoClient($__CONFIG->Mongo->path, [

                'db'               => $__CONFIG->Mongo->database,
                'username'         => $__CONFIG->Mongo->username,
                'password'         => $__CONFIG->Mongo->password,
                'connectTimeoutMS' => $__CONFIG->Mongo->timeout

            ]);

            return $mc->selectDB($__CONFIG->Mongo->database);

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

        $di = \Phalcon\DI::getDefault();

        $di->set('view', 'VJ\View');

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
        $di->setShared('session', function () use ($__CONFIG, $di) {

            $session = new \Phalcon\Session\Adapter\Mongo([
                'collection' => $di->getShared('mongo')->Session
            ]);

            $session->start();

            return $session;
        });

        $__SESSION = $di->getSession();

    }

}