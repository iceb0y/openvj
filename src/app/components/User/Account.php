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

        if (!isset($__SESSION['user'])) {

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

        global $_UID, $_NICK, $_ACL;

        $_UID  = $__SESSION['user']['uid'];
        $_NICK = $__SESSION['user']['nick'];
        $_ACL  = $__SESSION['user']['acl'];

    }

    /**
     * 删除用户
     *
     * @param      $uid
     * @param bool $permanent
     *
     * @return array|bool
     */
    public static function delete($uid, $permanent = false)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $uid = (int)$uid;

        if ($permanent) {

            if (!$acl->has(PRIV_USER_DELETE_PERM)) {
                return I::error('NO_PRIV', 'PRIV_USER_DELETE_PERM');
            }

            $result = $mongo->User->remove(
                ['uid' => $uid],
                ['justOne' => true]
            );

            return ($result['n'] === 1);

        } else {

            if (!$acl->has(PRIV_USER_DELETE_FLAG)) {
                return I::error('NO_PRIV', 'PRIV_USER_DELETE_FLAG');
            }

            $result = $mongo->User->update(
                ['uid' => $uid],
                ['$set' => ['deleted' => true]]
            );

            return ($result['n'] === 1);

        }

    }

    /**
     * 取消用户已删除的标记
     *
     * @param $uid
     *
     * @return array|bool
     */
    public static function unDelete($uid)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $uid = (int)$uid;

        if (!$acl->has(PRIV_USER_DELETE_FLAG)) {
            return I::error('NO_PRIV', 'PRIV_USER_DELETE_FLAG');
        }

        $result = $mongo->User->update(
            ['uid' => $uid],
            ['$unset' => ['deleted' => 1]]
        );

        return ($result['n'] === 1);

    }

    /**
     * 封禁用户
     *
     * @param $uid
     *
     * @return array|bool
     */
    public static function ban($uid)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $uid = (int)$uid;

        if (!$acl->has(PRIV_USER_BAN)) {
            return I::error('NO_PRIV', 'PRIV_USER_BAN');
        }

        $result = $mongo->User->update(
            ['uid' => $uid],
            ['$set' => ['banned' => true]]
        );

        return ($result['n'] === 1);

    }

    /**
     * 取消封禁用户
     *
     * @param $uid
     *
     * @return array|bool
     */
    public static function unBan($uid)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $uid = (int)$uid;

        if (!$acl->has(PRIV_USER_BAN)) {
            return I::error('NO_PRIV', 'PRIV_USER_BAN');
        }

        $result = $mongo->User->update(
            ['uid' => $uid],
            ['$unset' => ['banned' => 1]]
        );

        return ($result['n'] === 1);

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
     * 检查用户名是否存在
     *
     * @param      $username
     * @param null $uid
     *
     * @return bool
     */
    public static function usernameExists($username, &$uid = null)
    {

        $username = strtolower($username);

        $user = Models\User::findFirst([
            'conditions' => ['luser' => $username],
            'fields'     => ['uid' => 1]
        ]);

        if ($user) {
            $uid = (int)$user->uid;

            return true;
        } else {
            return false;
        }

    }

    /**
     * 检查昵称是否存在
     *
     * @param      $nickname
     * @param null $uid
     *
     * @return bool
     */
    public static function nicknameExists($nickname, &$uid = null)
    {

        $nickname = strtolower($nickname);

        $user = Models\User::findFirst([
            'conditions' => ['lnick' => $nickname],
            'fields'     => ['uid' => 1]
        ]);

        if ($user) {
            $uid = (int)$user->uid;

            return true;
        } else {
            return false;
        }

    }

}