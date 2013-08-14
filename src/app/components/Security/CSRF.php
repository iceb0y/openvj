<?php

namespace VJ\Security;

class CSRF
{

    /**
     * 初始化CSRF-token
     */
    public static function initToken()
    {

        global $__SESSION;

        if (!$__SESSION->has('csrf-token')) {
            $__SESSION->set('csrf-token', md5(uniqid().rand(1, 10000)));
        }

    }

}