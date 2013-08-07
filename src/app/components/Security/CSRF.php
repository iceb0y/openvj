<?php

namespace VJ\Security;

class CSRF
{

    public static function initToken($di)
    {

        if (!$di->getSession()->has('csrf-token')) {
            $di->getSession()->set('csrf-token', md5(uniqid().rand(1, 10000)));
        }

    }

}