<?php

namespace VJ\Session;

class Utils
{

    public static $provider;
    public static $sessname;
    public static $sessid;
    public static $save = false;

    public static function initialize(SessionProvider $provider)
    {

        global $__CONFIG, $__SESSION;

        self::$provider = $provider;
        self::$sessname = $__CONFIG->Session->name;

        register_shutdown_function([get_called_class(), 'commit']);

        // no sessions yet or session_id is invalid
        if (
            !isset($_COOKIE[self::$sessname])
            || strlen($_COOKIE[self::$sessname]) !== 20
            || ($__SESSION = self::$provider->getSession($_COOKIE[self::$sessname])) == false
        ) {

            $__SESSION = [];

            if ($__CONFIG->Session->ignoreGuest) {

                self::$save   = false;
                self::$sessid = self::generateGuestSessionId();

                if (isset($_COOKIE[self::$sessname])) {

                    unset($_COOKIE[self::$sessname]);
                    self::expireCookie();

                }

            } else {

                self::newSession();

            }

        } else {

            self::$save   = true;
            self::$sessid = $_COOKIE[self::$sessname];

        }

    }

    /**
     * 创建一个新Session并标记为可保存
     */
    public static function newSession()
    {

        global $__SESSION;

        $__SESSION = [];

        self::$save   = true;
        self::$sessid = self::generateSessionId();
        self::$provider->newSession(self::$sessid, $__SESSION);
        self::setCookie(self::$sessid);

    }

    /**
     * 销毁当前的Session
     */
    public static function destroy()
    {

        global $__SESSION;

        $__SESSION = [];

        if (self::$save) {
            self::$provider->deleteSession(self::$sessid);
            self::expireCookie();
        }
    }

    /**
     * 立即保存当前的Session
     */
    public static function commit()
    {

        if (!self::$save) {
            return;
        }

        global $__SESSION;

        self::$provider->saveSession(self::$sessid, $__SESSION);

    }

    /**
     * 使SESSION_ID过期
     *
     * @return bool
     */
    private static function expireCookie()
    {
        return \VJ\Utils::expireCookie(self::$sessname);
    }

    /**
     * 向客户端发送SESSION_ID
     *
     * @param $value
     *
     * @return bool
     */
    private static function setCookie($value)
    {
        return \VJ\Utils::setCookie(self::$sessname, $value, 0);
    }

    /**
     * 生成用于标记游客的SessionID
     *
     * @return string
     */
    private static function generateGuestSessionId()
    {
        return str_repeat('0', 20);
    }

    /**
     * 生成SessionID
     *
     * @return string
     */
    private static function generateSessionId()
    {
        return \VJ\Security\Randomizer::toHex(10);
    }

}