<?php

namespace VJ\Security;

class Session
{

    /**
     * 记录并检查会话的环境（User-Agent、IP）
     */
    public static function initCharacter()
    {

        global $SESSION;

        if (!$SESSION->has('session-ip')) {

            $SESSION->set('session-ip', $_SERVER['REMOTE_ADDR']);

        } else if ($SESSION->get('session-ip') !== $_SERVER['REMOTE_ADDR']) {

            echo 'Your IP has changed. Please re-login.';
            \VJ\User\Session::destroyCurrent();

            exit();

        }

        if (!$SESSION->has('session-ua')) {

            $SESSION->set('session-ua', $_SERVER['HTTP_USER_AGENT']);

        } else if ($SESSION->get('session-ua') !== $_SERVER['HTTP_USER_AGENT']) {

            echo 'Your User-Agent has changed. Please re-login.';
            \VJ\User\Session::destroyCurrent();

            exit();

        }

    }


}