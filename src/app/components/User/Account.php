<?php

namespace VJ\User;

use \VJ\I;
use \VJ\Models;

class Account
{

    /**
     * 处理COOKIE登录、初始化游客权限用户、提取Session
     */
    public static function initialize()
    {

        global $__SESSION;

        if (!$__SESSION->has('user')) {

            if (
                isset($_COOKIE['VJ_SESSION_TOKEN'])
                && isset($_COOKIE['VJ_SESSION_UID'])
                && isset($_COOKIE['VJ_SESSION_KEY'])
            ) {
                // Saved-session exists

                if (
                I::isError(\VJ\User\Account\Login::fromCookie(
                    $_COOKIE['VJ_SESSION_TOKEN'],
                    $_COOKIE['VJ_SESSION_UID'],
                    $_COOKIE['VJ_SESSION_KEY']
                ))
                ) {
                    \VJ\User\Account\Login::guest();
                }

            } else {

                \VJ\User\Account\Login::guest();

            }

        }

        global $_UID, $_NICK, $_PRIV;

        $_UID  = $__SESSION->get('user')['uid'];
        $_NICK = $__SESSION->get('user')['nick'];
        $_PRIV = $__SESSION->get('user')['priv'];

    }

    /**
     * 通过用户名、密码、salt计算最终密码哈希值
     * 该函数仅用于兼容数据库中已有的密码。
     *
     * @param      $username
     * @param      $password
     * @param      $salt
     * @param bool $isMD5
     *
     * @return string
     */
    public static function makeHash_deprecated($username, $password, $salt, $isMD5 = false)
    {

        $username = strtolower($username);

        if ($isMD5 !== true) {
            $password = md5($password);
        }

        return sha1(md5($username.$password).$salt.sha1($password.$salt));

    }

    /**
     * 使用密码和salt计算密码哈希值
     *
     * @param      $password
     * @param      $salt
     * @param bool $isMD5
     *
     * @return string
     */
    public static function makeHash($password, $salt, $isMD5 = false)
    {

        if ($isMD5 !== true) {
            $password = md5($password);
        }

        $hash = $password;

        for ($i = 0; $i < 5000; $i++) {
            $hash = hash('sha512', $hash.$salt);
        }

        return $hash;

    }

    /**
     * 检查一个用户名是否存在
     *
     * @param $username
     *
     * @return bool
     */
    public static function usernameExists($username)
    {

        $username = strtolower($username);

        $user = Models\User::findFirst([
            'conditions' => ['luser' => $username],
            'fields'     => ['_id' => 1]
        ]);

        if ($user) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 检查一个昵称是否存在
     *
     * @param $nick
     *
     * @return bool
     */
    public static function nicknameExists($nickname)
    {

        $nickname = strtolower($nickname);

        $user = Models\User::findFirst([
            'conditions' => ['lnick' => $nickname],
            'fields'     => ['_id' => 1]
        ]);

        if ($user) {
            return true;
        } else {
            return false;
        }

    }

}