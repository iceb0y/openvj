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

    /**
     * 检查CSRF-token
     *
     * @return bool
     */
    public static function checkToken()
    {

        global $__SESSION;

        if (!isset($__SESSION['csrf-token'])) {
			throw new \VJ\Exception('ERR_CSRF_TOKEN_MISSING');
        }

        $token = strval($__SESSION['csrf-token']);

		if (isset($_GET['token']) && strval($_GET['token']) === $token) {
            return true;
		}

        if (isset($_POST['token']) && strval($_POST['token']) === $token) {
			return true;
		}
    }

}
