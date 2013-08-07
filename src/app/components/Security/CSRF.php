<?php

namespace VJ\Security;

class CSRF
{

    public static function initToken()
    {

        global $SESSION;

        if (!$SESSION->has('csrf-token')) {
            $SESSION->set('csrf-token', md5(uniqid().rand(1, 10000)));
        }

    }

}