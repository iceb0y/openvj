<?php

namespace VJ\User;

class Session
{

    const SESSION_FIELD = 'session_id';

    public static function destroy($sessid)
    {

        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

        return $mongo->ActiveSession->remove([self::SESSION_FIELD => $sessid], ['justOne' => 1]);

    }

    public static function destroyCurrent()
    {

        $sessid = session_id();

        session_unset();
        session_destroy();

        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time() - 42000, '/', '.'.ENV_HOST, false, true);

        self::destroy($sessid);

    }

}