<?php

namespace VJ\User\Account;

class Login
{
    /**
     * 使用COOKIE登录
     *
     * @param $token
     * @param $uid
     * @param $key
     *
     * @return bool
     */
    public static function fromCookie($token, $uid, $key)
    {

        return true;

    }

    /**
     * 使用用户名密码登录
     *
     * @param $user
     * @param $pass_md5
     */
    public static function fromPass($user, $pass_md5)
    {


    }

    /**
     * 登录为游客权限用户
     *
     * @return bool
     */
    public static function guest()
    {

        global $SESSION, $_GROUPPRIV;

        $SESSION->set('user', array(

            'id'       => UID_GUEST,
            'nick'     => NICK_GUEST,
            'gmd5'     => '',
            'group'    => GROUP_GUEST,
            'rank'     => 0,
            'rp'       => 0.0,
            'vjb'      => 0.0,
            'priv'     => $_GROUPPRIV[GROUP_GUEST],
            'settings' => array()

        ));

        return true;

    }

}