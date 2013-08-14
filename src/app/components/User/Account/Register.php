<?php

namespace VJ\User\Account;

class Register
{

    public static function sendVerificationEmail($email)
    {

        $email = strtolower((string)$email);

        if (\VJ\Utils::len($email) > 40) {
            return \VJ\I::error('ARGUMENT_INVALID', 'email');
        }

        if (!\VJ\Validator::email($email)) {
            return \VJ\I::error('ARGUMENT_INVALID', 'email');
        }

        global $mongo;
        if ($mongo->User->findOne(array('mail' => $email), array('_id' => 1)) !== null)
            return \VJ\I::error('USED', 'email', $email);

        $validateCode = \VJ\Security\Randomizer::toHex(10);

        $mongo->RegValidation->update(

            array('email' => $email),
            array('$set' => array(

                'code' => $validateCode,
                'time' => new \MongoDate()

            )),
            array('upsert' => true)

        );

        // TODO: Re-implement needed

        /*
        $url = ENV_HOST_URL.'/user/register?code='.urlencode($validateCode).'&email='.urlencode($email);
        $body = 'Verification_URL: '.$url;
        $ret = \VJ\Email::send($email, '['.APP_NAME.'] Register Verification ', $body);

        if ($ret === true)
            return true;
        else
            return \VJ\I::error('EMAIL_SEND_FAILED', $ret);
        */
    }

    /**
     * 检查email & code并设置状态
     *
     * @param $email
     * @param $code
     *
     * @return bool|ErrorObject
     */
    public static function verificateEmail($email, $code)
    {
        $email = strtolower((string)$email);
        $code  = strval($code);

        global $mongo, $__SESSION, $__CONFIG;

        $verify = $mongo->RegValidation->findOne(array('email' => $email, 'code' => $code));

        if ($verify == null) {
            return \VJ\I::error('REG_VERFICATION_FAILED');
        }

        if (time() - $verify->time->sec > (int)$__CONFIG->Register->validationExpire) {
            return \VJ\I::error('REG_VERFICATION_EXPIRED');
        }

        $__SESSION->set('reg-email', $email);
        $__SESSION->set('reg-code', $code);

        return true;
    }

    /**
     * 注册新用户
     *
     * @param      $username
     * @param      $password
     * @param      $sex
     * @param      $agreement
     * @param null $options
     *
     * @return bool|ErrorObject
     */
    public static function register($username, $password, $sex, $agreement, $options = null)
    {
        return \VJ\I::error('NOT_IMPLEMENTED');


        global $mongo, $__SESSION;

        if (strtolower($agreement) !== 'accept')
            return \VJ\I::error('REG_ACCEPT_AGREEMENT_NEEDED');

        $username = trim(strval($username));
        $password = strval($password);
        $sex      = intval($sex);

        $oUser = $username;
        $oPass = $password;

        if ($options == null)
            $options = array();

        //校验有效性
        if ($sex !== 0 && $sex !== 1 && $sex !== 2)
            return \VJ\I::error('REG_SEX_INVALID');

        if (mb_strlen($username, 'UTF-8') < 3 || mb_strlen($username, 'UTF-8') > 16)
            return \VJ\I::error('REG_USER_INVALID');

        if (!preg_match('/^[^ ^\t]*$/', $username))
            return \VJ\I::error('REG_USER_INVALID');

        if (!preg_match('/^.{5,30}$/', $password) && !isset($options['use_new_pass']) && !isset($options['use_md5']))
            return \VJ\I::error('REG_PASS_INVALID');

        $cUser = $mongo->User;
        if ($cUser->findOne(array('luser' => strtolower($username)), array('_id' => 1)) !== null)
            return \VJ\I::error('REG_USER_EXIST');

        $username = \VJ\Escaper::html($username);

        if (isset($options['email']))
            $email = $options['email'];
        else
            $email = $__SESSION->get('reg_email');

        $email = strtolower(trim(strval($email)));

        if (!isset($options['no_code'])) {
            $code = strval($__SESSION->get('reg-code'));

            $ret = self::verificateEmail($email, $code); //再次检查
            if ($ret !== true) {
                return $ret;
            }
        }

        //生成salt
        if (isset($options['use_new_pass'])) {
            $salt     = strval($options['salt']);
            $password = strval($options['password']);
        } else if (isset($options['use_md5'])) {
            $salt     = sha1(uniqid().mt_rand(1, 100000));
            $password = \VJ\User\Account::makeHash($username, $options['password'], $salt, true);
        } else {
            $salt     = sha1(uniqid().mt_rand(1, 100000));
            $password = \VJ\User\Accountt::makeHash($username, $password, $salt);
        }

        // **TODO: UID COUNTER
        if (isset($options['uid']))
            $newId = intval($options['uid']);
        else
            $newId = 0;

        if (isset($options['nickname']))
            $newNick = \VJ\Escaper::html($options['nickname']);
        else
            $newNick = '';

        if (isset($options['rp']))
            $newRp = floatval($options['rp']);
        else
            $newRp = 0.0;

        if (isset($options['vjb']))
            $newVjb = floatval($options['vjb']);
        else
            $newVjb = 0.0;


        if (isset($options['sig']))
            $newSig = strval($options['sig']);
        else
            $newSig = '';

        if (isset($options['group']))
            $newGroup = intval($options['group']);
        else
            $newGroup = GROUP_USER;

        //删除注册码
        $mongo->RegValidation->remove(array('email' => $email));

        $regData = array
        (
            '_id'      => $newId,
            'user'     => $username,
            'luser'    => strtolower($username),
            'pass'     => $password,
            'nick'     => $newNick,
            'salt'     => $salt,
            'mail'     => $email,
            'qq'       => '',
            'g'        => $email, //gravatar
            'gmd5'     => md5($email),
            'sex'      => $sex,
            'tlogin'   => 0,
            'treg'     => time(),
            'ipreg'    => $_SERVER['REMOTE_ADDR'],
            'priv'     => array('_' => null),
            'group'    => $newGroup,
            'team'     => array(),
            'pbms'     => array
            (
                'pass'    => 0,
                'passlst' => array(),
                'ans'     => 0,
                'anslst'  => array(),
                'submit'  => 0
            ),
            'settings' => array()
        );

        $cUser->insert($regData);

        $__SESSION->remove('reg-email');
        $__SESSION->remove('reg-code');


        if (!isset($options['no_login']))
            \VJ\User\Account\Login::fromPassword($oUser, $oPass);

        return true;
    }

}