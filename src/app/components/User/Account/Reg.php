<?php

namespace VJ\User\Account;

class Reg
{
    /**
     ** TODO: ADD MONGODB TTL AT ANOTHER PLACE
     * 发送验证邮件
     * 
     * @param $email
     *
     * @return bool|ErrorObject
     */
    public static function sendVerificationEmail($email)
    {

        $email = strtolower(strval($email));
        if (strlen($email) > 30)
            return \VJ\I::error('EMAIL_MISMATCH');
        if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email))
            return \VJ\I::error('EMAIL_MISMATCH');

        global $mongo, $SESSION;
        if ($mongo->User->findOne(array('mail' => $email), array('_id' => 1)) !== null)
            return \VJ\I::error('EMAIL_EXIST');

        $validateCode = sha1(uniqid().mt_rand(1,100000));

        //数据库插入
        $mongo->RegValidation->update
            (
                array('email' => $email),
                array('$set' => array
                (
                    'code' => $validateCode,
                    'time' => time()
                )),
                array('upsert' => true)
            );

        $url = ENV_HOST_URL.'/user/register?code='.urlencode($validateCode).'&email='.urlencode($email);
        $body = 'Verification_URL: '.$url;
        $ret = \VJ\Email::send($email, '['.APP_NAME.'] Register Verification ', $body);

        if ($ret === true)
            return true;
        else
            return \VJ\I::error('EMAIL_SEND_FAILED', $ret);
    }
    /**
     * 检查email & code并设置状态
     *
     * @param $email
     * @param $code
     *
     * @return bool|ErrorObject
     */
    public static function setValidationState($email, $code)
    {
        $email = strtolower(strval($email));
        $code  = strval($code);

        global $mongo, $SESSION;
        $verify = $mongo->RegValidation->findOne
            (
                array('email' => $email, 'code' => $code)
            );

        if ($verify == null)
            return \VJ\I::error('EMAIL_VALIDATION_FAILED');
    
        $SESSION->set('reg_email', $email);
        $SESSION->set('reg_code', $code);
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
    function register($username, $password, $sex, $agreement, $options = null)
    {
        global $mongo, $SESSION;

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

        $cUser = $db->selectCollection('User');
        if ($cUser->findOne(array('luser' => strtolower($username)), array('_id' => 1)) !== null)
            return \VJ\I::error('REG_USER_EXIST');

        $username = \VJ\Escaper::html($username);

        if (isset($options['email']))
            $email = $options['email'];
        else
            $email = $SESSION->get('reg_email');

        $email = strtolower(trim(strval($email)));

        if (!isset($options['no_code'])) {
            $code = strval($SESSION->get('reg_code'));

            $ret = VJ\User\Account\Reg::setValidationState($email, $code); //再次检查
            if ($ret !== true) return $ret;
        }

        //生成salt
        if (isset($options['use_new_pass'])) {
            $salt     = strval($options['salt']);
            $password = strval($options['password']);
        } else if (isset($options['use_md5'])) {
            $salt     = sha1(uniqid().mt_rand(1,100000));
            $password = usrEncrypt(strtolower($username), $options['password'], $salt, true);
        } else {
            $salt     = sha1(uniqid().mt_rand(1,100000));
            $password = usrEncrypt(strtolower($username), $password, $salt);
        }

        if (isset($options['uid']))
            $newId = intval($options['uid']);
        else
            $newId = \mongodb\getFieldCounter(MONGO_COUNTER_USER_ID);
        
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

        if (isset($options['rank']))
            $newRank = intval($options['rank']);
        else
            $newRank = 0;

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
            'rp'       => $newRp,
            'vjb'      => $newVjb,
            'g'        => $email, //gravatar
            'gmd5'     => md5($email),
            'sig'      => $newSig,
            'sigm'     => '',
            'page'     => '',
            'sex'      => $sex,
            'tlogin'   => 0,
            'treg'     => time(),
            'ipreg'    => $_SERVER['REMOTE_ADDR'],
            'priv'     => array('_' => null),
            'privacy'  => array('_' => null),
            'stars'    => array('_' => null),
            'group'    => $newGroup,
            'team'     => array(),
            'rank'     => $newRank,
            'pbms'     => array
            (
                'pass'    => 0,
                'passlst' => array(),
                'ans'     => 0,
                'anslst'  => array(),
                'submit'  => 0
            ),
            'settings' => array
            (
                'first_reg' => true,
                'tags'      => 1
            )
        );

        $cUser->insert($regData);

        $SESSION->remove('reg_email');
        $SESSION->remove('reg_code');


        if (!isset($options['no_login']))
            VJ\User\Account\Login::fromPassword($oUser, $oPass);

        return true;
    }
    /** TODO
    * 增加注册记录
    * @param $uid
    * @param $from
    * @param $ok
    */
}