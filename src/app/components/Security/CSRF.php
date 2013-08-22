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

        if (!isset($__SESSION['csrf-token'])) {
            if (\VJ\Session\Utils::$save) {
                $__SESSION['csrf-token'] = \VJ\Security\Randomizer::toHex(10);
            } else {
                $__SESSION['csrf-token'] = str_repeat('0', 20);
            }
        }

    }

}