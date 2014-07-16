<?php

namespace VJ\User\Account;

use \VJ\I;
use \VJ\Utils;
use \VJ\Models;

class Register
{

    /**
     * 发送验证邮件
     *
     * @param $email
     *
     * @return array|bool
     */
    public static function sendVerificationEmail($email)
    {

        $email = strtolower((string)$email);

        if (Utils::len($email) > 40) {
			throw new \VJ\Exception('ERR_ARGUMENT_INVALID','email');
        }

        if (!\VJ\Validator::email($email)) {
			throw new \VJ\Exception('ERR_ARGUMENT_INVALID','email');
        }

        // Mail already in use
        if (
        Models\User::findFirst([
            'conditions' => ['mail' => $email],
            'fields'     => ['_id' => 1]
        ])
        ) {
			throw new \VJ\Exception('ERR_USED','email',$email);
        }

        // Generate new validation request
        $validateCode = \VJ\Security\Randomizer::toHex(10);

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $mongo->RegValidation->update(['email' => $email], [
            '$set' => [
                'code' => $validateCode,
                'time' => new \MongoDate()
            ]
        ], ['upsert' => true]);

        // Send validation email
        global $__CONFIG;

        if ($__CONFIG->Security->enforceSSL) {
            $prefix = 'https://';
        } else {
            $prefix = 'http://';
        }

        $URI = $prefix.$__CONFIG->Misc->host.$__CONFIG->Misc->basePrefix.'/user/register?';
        $URI .= \VJ\Escaper::uriQuery([
            'code'  => $validateCode,
            'email' => sha1($email)
        ]);

        \VJ\Email::sendByTemplate(
            $email,
            gettext('Just one more step!'),
            'user',
            'reg_validation',
            [
                'TITLE'   => gettext('Email validation'),
                'REG_URI' => $URI
            ]
        );

        return true;
    }

    /**
     * 检查邮件验证
     *
     * @param $email
     * @param $code
     *
     * @return array|bool
     */
    public static function verificateEmail($mailHash, $code)
    {

        global $__CONFIG;

        $code = (string)$code;

        $record = Models\RegValidation::findFirst([
            'conditions' => ['code' => $code]
        ]);

        if (!$record) {
			throw new \VJ\Exception('ERR_REG_VERFICATION_FAILED');
            
        }

        if (sha1(strtolower($record->email)) !== (string)$mailHash) {
			throw new \VJ\Exception('ERR_REG_VERFICATION_FAILED');
            
        }

        if (time() - $record->time->sec > (int)$__CONFIG->Register->validationTTL) {
			throw new \VJ\Exception('ERR_REG_VERFICATION_EXPIRED');
            
        }

        return ['mail' => $record->email, 'code' => $code];
    }

    /**
     * 注册新用户
     *
     * @param      $username
     * @param      $password
     * @param      $nickname
     * @param      $gender
     * @param      $agreement
     * @param null $options
     *
     * @return array|bool
     */
    public static function register($username, $password, $nickname, $gender, $agreement, $options = [])
    {
        /*
            Options:

                email:          string  Email
                code:           string  Email verification code

                no_checking:    bool    是否检查Email验证
                uid:            int     指定UID
                ipmatch:        regex   匹配指定登录IP

        */

        if (strtolower($agreement) !== 'accept') {
			throw new \VJ\Exception('ERR_REG_ACCEPT_NEEDED');
            
        }

        $data = [
            'username' => &$username,
            'password' => &$password,
            'nickname' => &$nickname,
            'gender'   => &$gender
        ];

        \VJ\Validator::filter($data, [
            'username' => ['trim', 'lower'],
            'password' => 'string',
            'nickname' => 'trim',
            'gender'   => 'int'
        ]);

        $validateResult = \VJ\Validator::validate($data, [
            'username' => [
                'regex' => '/^[^ ^\t]{3,30}$/'
            ],
            'nickname' => [
                'regex' => '/^[^ ^\t]{1,15}$/'
            ],
            'password' => [
                'regex' => '/^.{5,30}$/'
            ],
            'gender'   => [
                'in' => [0, 1, 2]
            ]
        ]);

        if ($validateResult !== true) {
            return $validateResult;
        }

        // Exists?
        if (\VJ\User\Account::usernameExists($username)) {
			throw new \VJ\Exception('ERR_USED','username',$username);
            
        }

        if (\VJ\User\Account::nicknameExists($nickname)) {
			throw new \VJ\Exception('ERR_USED','username',$nickname);
            
        }

        // Check session
        if (!isset($options['no_checking'])) {

            if (!isset($options['email']) || !isset($options['code'])) {
				throw new \VJ\Exception('ERR_REG_VERFICATION_FAILED');
                
            }

            $mail           = $options['email'];
            $validateResult = self::verificateEmail(sha1($mail), $options['code']);

            // Remove validation records
            $validate_record = Models\RegValidation::findFirst([
                'conditions' => ['email' => $mail]
            ]);

            if ($validate_record) {
                $validate_record->delete();
            } else {
				throw new \VJ\Exception('ERR_REG_VERFICATION_FAILED');
                
            }

            unset($validate_record);

        } else {

            $mail = '';

        }

        // Begin
        $salt = \VJ\Security\Randomizer::toHex(30);
        $pass = \VJ\User\Account::makeHash($password, $salt);

        if (isset($options['uid'])) {
            $uid = (int)$options['uid'];
        } else {
            $uid = \VJ\Database::increaseId(\VJ\Database::COUNTER_USER_ID);
        }

        $user           = new Models\User();
        $user->uid      = $uid;
        $user->luser    = $username;
        $user->nick     = $nickname;
        $user->lnick    = strtolower($nickname);
        $user->salt     = $salt;
        $user->pass     = $pass;
        $user->passfmt  = 1; //password format 1
        $user->mail     = $mail;
        $user->qq       = '';
        $user->rp       = 0.0;
        $user->vjb      = 0.0;
        $user->rank     = 0;
        $user->g        = $mail; //gravatar
        $user->gmd5     = md5($mail);
        $user->gender   = $gender;
        $user->tlogin   = time();
        $user->iplogin  = '';
        $user->treg     = time();
        $user->ipreg    = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $user->sig      = '';
        $user->sigm     = '';
        $user->group    = GROUP_USER;
        $user->acl      = serialize([]); //avoid unnecessary unserialization
        $user->aclrule  = serialize([]);
        $user->privacy  = new \stdClass();
        $user->stars    = new \stdClass();
        $user->pbms     = [
            'pass'    => 0,
            'passlst' => [],
            'ans'     => 0,
            'anslst'  => [],
            'submit'  => 0
        ];
        $user->settings = [
            'first_reg' => true
        ];

        if (isset($options['ipmatch'])) {
            $user->ipmatch = $options['ipmatch'];
        }

        $user->save();

        return [
            'uid' => $uid
        ];
    }

}
