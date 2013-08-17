<?php

namespace VJ;

class Phalcon
{

    /**
     * 初始化Session
     *
     * Session Security Issues [TODO]:
     *
     * 1. session should be locked during a request
     * 2. should use a low-cost way to deal with guest sessions
     *
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
                'collection' => $di->getShared('mongo')->ActiveSession
            ]);

            $session->start();

            return $session;
        });

        $__SESSION = $di->getSession();

    }

}