<?php

namespace VJ\User\Account;

class Login
{

    const LOGIN_FROM_AUTH   = 0;
    const LOGIN_FROM_COOKIE = 1;
    const LOGIN_FROM_CERT   = 2;
    const LOGIN_FROM_API    = 3;
    const LOGIN_FROM_SSH    = 4;
    const LOGIN_FROM_MC     = 5;

    /**
     * 使用COOKIE登录
     *
     * @param $token
     * @param $uid
     * @param $key
     *
     * @return array
     */
    public static function fromCookie($token, $uid, $key)
    {

        $token = \VJ\Validator::mongoId($token);
        if ($token == null) {
            return \VJ\I::error('ARGUMENT_INVALID', 'token');
        }

        $uid = (int)$uid;
        $key = (string)$key;

        global $mongo;
        $res = $mongo->SavedSession->findOne(array('_id' => $token));

        if ($res == null) {
            return \VJ\I::error('FAILED');
        }

        // Valid?
        if ($res['_id'] !== $uid || $res['key'] !== $key) {
            return \VJ\I::error('FAILED');
        }

        // Session expired?
        if (time() > $res['exptime']->sec) {

            $mongo->SavedSession->remove(array('_id' => $token), array('justOne' => true));
            return \VJ\I::error('FAILED');

        }

        $res = $mongo->User->findOne(array('_id' => $uid));
        // User is deleted
        if ($res == null) {
            return \VJ\I::error('FAILED');
        }

        // Login succeeded
        self::_log($uid, self::LOGIN_FROM_COOKIE, true);

        unset($res['salt'], $res['pass']);

        return $res;

    }

    /**
     * 尝试使用用户名密码登录
     *
     * @param     $user
     * @param     $pass_md5
     * @param int $from
     *
     * @return array
     */
    public static function fromPass($user, $pass_md5, $from = self::LOGIN_FROM_AUTH)
    {

        $user     = strtolower(\VJ\Escaper::html($user));
        $pass_md5 = (string)$pass_md5;

        if (strlen($user) === 0) {
            return \VJ\I::error('ARGUMENT_REQUIRED', 'username');
        }

        if (strlen($pass_md5) === 0) {
            return \VJ\I::error('ARGUMENT_REQUIRED', 'password');
        }

        global $mongo;
        $res = $mongo->User->findOne(array('luser' => $user));

        // No such user
        if ($res == null) {
            return \VJ\I::error('USER_NOTFOUND');
        }

        if ($res['pass'] !== \VJ\User\Account::makeHash($user, $pass_md5, $res['salt'], true)) {
            $login_OK = false;
        } else {
            $login_OK = true;
        }

        self::_log($res['_id'], $from, $login_OK);

        if (!$login_OK) {
            return \VJ\I::error('PASSWORD_WRONG');
        }

        unset($res['salt'], $res['pass']);

        return $res;

    }

    /**
     * 增加登录记录
     *
     * @param $uid
     * @param $from
     * @param $ok
     */
    private static function _log($uid, $from, $ok)
    {

        global $mongo;

        $uid = (int)$uid;
        $from = (int)$from;
        $ok = (bool)$ok;

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = \VJ\Escaper::html(\VJ\Utils::strcut($_SERVER['HTTP_USER_AGENT']));
        } else {
            $ua = '';
        }

        $mongo->LoginInfo->insert(array(

            'time' => new \MongoDate(),
            'uid'  => $uid,
            'ok'   => $ok,
            'from' => $from,
            'ip'   => \VJ\Escaper::html($_SERVER['REMOTE_ADDR']),
            'ua'   => $ua

        ));

    }

    /**
     * 登录为用户
     *
     * @param $data
     *
     * @return array|bool
     */
    public static function user($data)
    {

        global $mongo, $SESSION, $_GROUPPRIV;

        $priv = $data['priv'] + $_GROUPPRIV[(int)$data['group']];

        // 检查该账号是否可登录
        if (!isset($priv[PRIV_LOG_IN]) || $priv[PRIV_LOG_IN] !== false) {
            return \VJ\I::error('NO_PRIV', 'PRIV_LOG_IN');
        }

        // 修改最后登录时间
        $mongo->User->update(array('_id' => $data['_id']), array(
            '$set' => array(

                'tlogin' => time(),
                'iplogin' => \VJ\Escaper::html($_SERVER['REMOTE_ADDR'])

            )
        ));

        $u_data = \VJ\Validator::filter($data, array(

            'nick' => null,
            'gmd5' => null,
            'group' => 'int',
            'rank' => null,
            'rp' => null,
            'vjb' => null,
            'settings' => null

        ));

        $u_data['id'] = (int)$data['_id'];
        $u_data['priv'] = $priv;

        $SESSION->set('user', $u_data);

        return true;

    }

    /**
     * 登录为游客
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