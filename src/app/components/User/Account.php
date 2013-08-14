<?php

namespace VJ\User;

class Account
{

    /**
     * 处理COOKIE登录、初始化游客权限用户、提取Session
     */
    public static function initialize()
    {

        global $__SESSION;

        if (!$__SESSION->has('user-id')) {

            if (
                isset($_COOKIE['VJ_SESSION_TOKEN'])
                && isset($_COOKIE['VJ_SESSION_UID'])
                && isset($_COOKIE['VJ_SESSION_KEY'])
            ) {
                // Saved-session exists

                if (
                \VJ\I::isError(\VJ\User\Account\Login::fromCookie(
                    $_COOKIE['VJ_SESSION_TOKEN'],
                    $_COOKIE['VJ_SESSION_UID'],
                    $_COOKIE['VJ_SESSION_KEY']))
                ) {
                    \VJ\User\Account\Login::guest();
                }

            } else {

                \VJ\User\Account\Login::guest();

            }

        }

        global $_UID, $_NICK, $_PRIV;

        $_UID  = $__SESSION->get('user')['id'];
        $_NICK = $__SESSION->get('user')['nick'];
        $_PRIV = $__SESSION->get('user')['priv'];

    }

    /**
     * 通过用户名、密码、salt计算最终密码哈希值
     *
     * @param      $username
     * @param      $password
     * @param      $salt
     * @param bool $isMD5
     *
     * @return string
     */
    public static function makeHash($username, $password, $salt, $isMD5 = false)
    {
        $username = strtolower($username);

        if ($isMD5 !== true) {
            $password = md5($password);
        }

        return sha1(md5($username.$password).$salt.sha1($password.$salt));

    }

}