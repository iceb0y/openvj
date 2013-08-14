<?php

namespace VJ\Security;

class Session
{

    /**
     * 记录并检查会话的环境（User-Agent、IP）
     */
    public static function initCharacter()
    {

        global $__SESSION;

        if (!$__SESSION->has('session-ip')) {

            $__SESSION->set('session-ip', $_SERVER['REMOTE_ADDR']);

        } else if ($__SESSION->get('session-ip') !== $_SERVER['REMOTE_ADDR']) {

            echo 'Your IP has changed. Please re-login.';
            \VJ\User\Session::destroyCurrent();

            exit();

        }

        if (!$__SESSION->has('session-ua')) {

            $__SESSION->set('session-ua', $_SERVER['HTTP_USER_AGENT']);

        } else if ($__SESSION->get('session-ua') !== $_SERVER['HTTP_USER_AGENT']) {

            echo 'Your User-Agent has changed. Please re-login.';
            \VJ\User\Session::destroyCurrent();

            exit();

        }

    }


}