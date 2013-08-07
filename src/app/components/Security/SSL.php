<?php

namespace VJ\Security;

class SSL
{

    public static function force()
    {

        global $SESSION;

        if (isset($_GET['nossl'])) {

            $option = strtolower($_GET['nossl']);

            if ($option === 'false' || $option === 'off') {
                $SESSION->remove('option-nossl');
            } else {
                $SESSION->set('option-nossl', true);
            }

        }

        if (!ENV_SSL && !$SESSION->has('option-nossl')) {

            if
            (
                !isset($_SERVER['HTTP_USER_AGENT'])
                || stripos($_SERVER['HTTP_USER_AGENT'], 'Baiduspider') === false
                && stripos($_SERVER['HTTP_USER_AGENT'], 'Sogou web spider') === false
                && stripos($_SERVER['HTTP_USER_AGENT'], 'Sosospider') === false
            ) {

                header('HTTP/1.1 301 Moved Permanently');
                header('Location: https://'.ENV_HOST.$_SERVER['REQUEST_URI']);
                exit();

            }

        }

    }

}