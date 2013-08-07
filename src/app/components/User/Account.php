<?php

namespace VJ\User;

class Account {

    public static function initialize()
    {

        global $SESSION;

        if (!$SESSION->has('user-id')) {

            if (
                isset($_COOKIE['VJ_SESSION_TOKEN'])
                && isset($_COOKIE['VJ_SESSION_UID'])
                && isset($_COOKIE['VJ_SESSION_KEY'])
            ) {
                // Saved-session exists

                if (
                \VJ\I::isError(\VJ\User\Account\Login::fromCookie(
                    $_COOKIE['VJ_SESSION_TOKEN'],
                    $_COOKIE['VJ_SESSION_UID'],
                    $_COOKIE['VJ_SESSION_KEY']))
                ) {
                    \VJ\User\Account\Login::guest();
                }

            } else {

                \VJ\User\Account\Login::guest();

            }

        }

        global $_UID, $_NICK, $_PRIV;

        $_UID  = $SESSION->get('user')['id'];
        $_NICK = $SESSION->get('user')['nick'];
        $_PRIV = $SESSION->get('user')['priv'];

    }

}