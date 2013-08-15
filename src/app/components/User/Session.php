<?php

namespace VJ\User;

class Session
{

    const SESSION_FIELD = 'session_id';

    public static function destroy($sessid)
    {

        global $mongo;

        return $mongo->Session->remove(array(SESSION_FIELD => $sessid), array('justOne' => 1));

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