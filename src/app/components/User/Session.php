<?php

namespace VJ\User;

class Session
{

    const REDIS_PREFIX = 'PHPREDIS_SESSION:';

    public static function destroy($sessid)
    {

        global $redis;

        return $redis->delete(self::REDIS_PREFIX.$sessid);

    }

    public static function destroyCurrent()
    {

        global $__CONFIG;

        $sessid = session_id();

        session_unset();
        session_destroy();

        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time() - 42000, '/', '.'.$__CONFIG->Misc->host, false, true);

        self::destroy($sessid);

    }

}