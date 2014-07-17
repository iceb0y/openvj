<?php

namespace VJ\Session;

class Utils
{

    public static $provider;
    public static $sessname;
    public static $sessid;
    public static $save = false;

    const SESSION_ID_LENGTH = 44;

    public static function initialize(SessionProvider $provider)
    {
        global $__CONFIG, $__SESSION;

        self::$provider = $provider;
        self::$sessname = $__CONFIG->Session->name;

        register_shutdown_function([get_called_class(), 'commit']);

        // no sessions yet or session_id is invalid
        if (
            !isset($_COOKIE[self::$sessname])
            || strlen($_COOKIE[self::$sessname]) !== self::SESSION_ID_LENGTH
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
                self::sendNoCache();
            }
        } else {

            self::$save   = true;
            self::$sessid = $_COOKIE[self::$sessname];
            self::sendNoCache();
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
     * 强制浏览器不缓存
     */
    private static function sendNoCache()
    {
        header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
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
        return str_repeat('0', self::SESSION_ID_LENGTH);
    }

    /**
     * 生成SessionID
     *
     * @return string
     */
    private static function generateSessionId()
    {
        return uniqid('', true).'.'.\VJ\Security\Randomizer::toHex(10);
    }
}