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
            $__SESSION['csrf-token'] = \VJ\Security\Randomizer::toHex(10);
        }

    }

}