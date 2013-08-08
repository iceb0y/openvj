<?php

namespace VJ\User\Account;

class Login
{

    public static function fromCookie($token, $uid, $key)
    {

        return true;

    }

    public static function guest()
    {

        global $SESSION, $_GROUPPRIV;

        $SESSION->set('user', array(

            'id'       => UID_GUEST,
            'nick'     => NICK_GUEST,
            'gmd5'     => '',
            'group'    => GROUP_GUEST,
            'rank'     => 0,
            'rp'       => 0.0,
            'vjb'      => 0.0,
            'priv'     => $_GROUPPRIV[GROUP_GUEST],
            'settings' => array()

        ));

    }

}