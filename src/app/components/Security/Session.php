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

        if (!\VJ\Session\Utils::$save) {
            return;
        }

        if (!isset($__SESSION['session-ip'])) {

            $__SESSION['session-ip'] = $_SERVER['REMOTE_ADDR'];

        } else if ($__SESSION['session-ip'] !== $_SERVER['REMOTE_ADDR']) {

            echo 'Your IP has changed. Please re-login.';
            \VJ\Session\Utils::destroy();

            exit();

        }

        if (!isset($__SESSION['session-ua'])) {

            $__SESSION['session-ua'] = $_SERVER['HTTP_USER_AGENT'];

        } else if ($__SESSION['session-ua'] !== $_SERVER['HTTP_USER_AGENT']) {

            echo 'Your User-Agent has changed. Please re-login.';
            \VJ\Session\Utils::destroy();

            exit();

        }

    }


}