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
            return I::error('ARGUMENT_INVALID', 'email');
        }

        if (!\VJ\Validator::email($email)) {
            return I::error('ARGUMENT_INVALID', 'email');
        }

        // Mail already in use
        if (Models\User::findFirst(array(
            'conditions' => array('mail' => $email),
            'fields'     => array('_id' => 1)
        ))
        ) {
            return I::error('USED', 'email', $email);
        }

        // Generate new validation request
        $validateCode = \VJ\Security\Randomizer::toHex(10);

        $record = Models\RegValidation::findFirst(array(
            'conditions' => array('email' => $email)
        ));

        if (!$record) {
            $record        = new Models\RegValidation();
            $record->email = $email;
        }

        $record->code = $validateCode;
        $record->time = new \MongoDate();

        $record->save();

        // Send validation email
        global $__CONFIG;

        if ($__CONFIG->Security->enforceSSL) {
            $prefix = 'https://';
        } else {
            $prefix = 'http://';
        }

        $URI = $prefix.$__CONFIG->Misc->host.$__CONFIG->Misc->basePrefix.'/user/register?';
        $URI .= \VJ\Escaper::uriQuery(array(
            'code'  => $validateCode,
            'email' => sha1($email)
        ));

        return \VJ\Email::sendByTemplate(
            $email,
            gettext('Just one more step!'),
            'user',
            'reg_validation',
            array(
                'TITLE'   => gettext('Email validation'),
                'REG_URI' => $URI
            )
        );
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

        global $__CONFIG, $__SESSION;

        $code = (string)$code;

        $record = Models\RegValidation::findFirst(array(
            'conditions' => array('code' => $code)
        ));

        if (!$record) {
            return I::error('REG_VERFICATION_FAILED');
        }

        if (sha1(strtolower($record->email)) !== (string)$mailHash) {
            return I::error('REG_VERFICATION_FAILED');
        }

        if (time() - $record->time->sec > (int)$__CONFIG->Register->validationExpire) {
            return I::error('REG_VERFICATION_EXPIRED');
        }

        $__SESSION->set('reg-email', $record->email);
        $__SESSION->set('reg-code', $record->code);

        return true;
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
    public static function register($username, $password, $nickname, $gender, $agreement, $options = null)
    {
        /*
            Options:

                no_session_checking:    bool    是否检查Email验证
                uid:                    int     指定UID

        */

        if (strtolower($agreement) !== 'accept') {
            return I::error('REG_ACCEPT_NEEDED');
        }

        if ($options == null) {
            $options = [];
        }

        $data = [
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'gener'    => $gender
        ];

        $data = \VJ\Validator::filter($data, [
            'username' => ['trim', 'lower'],
            'password' => 'string',
            'nickname' => 'trim',
            'gender'   => 'int'
        ]);

        $validateResult = \VJ\Validator::validate($data, [
            'username' => [
                'length' => [3, 30],
                'regex'  => '/^[^ ^\t]*$/'
            ],
            'nickname' => [
                'length' => [1, 15],
                'regex'  => '/^[^ ^\t]*$/'
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
        if (\VJ\User\Account::usernameExists($data['username'])) {
            return I::error('USED', 'username', $data['username']);
        }

        if (\VJ\User\Account::nicknameExists($data['nickname'])) {
            return I::error('USED', 'nickname', $data['nickname']);
        }

        // Check session
        if (!isset($options['no_session_checking'])) {

            global $__SESSION;

            if (!$__SESSION->has('reg-email') || !$__SESSION->has['reg-code']) {
                return I::error('REG_VERFICATION_FAILED');
            }

            $mail           = $__SESSION->get('reg-email');
            $validateResult = self::verificateEmail(sha1($mail), $__SESSION->get('reg-code'));

            if ($validateResult !== true) {
                return $validateResult;
            }

            // Remove validation records
            $validate_record = Models\RegValidation::findFirst(array(
                'conditions' => array('email' => $mail)
            ));

            if ($validate_record) {
                $validate_record->delete();
            } else {
                return I::error('REG_VERFICATION_FAILED');
            }

            unset($validate_record);

        } else {

            $mail = '';

        }

        // Begin
        $salt = \VJ\Security\Randomizer::toHex(30);
        $pass = \VJ\User\Account::makeHash($data['password'], $salt);

        if (isset($options['uid'])) {
            $uid = (int)$options['uid'];
        } else {
            $uid = \VJ\Database::increaseId(\VJ\Database::COUNTER_USER_ID);
        }

        $user           = new Models\User();
        $user->_id      = $uid;
        $user->luser    = $data['username'];
        $user->nick     = $data['nickname'];
        $user->lnick    = strtolower($data['nickname']);
        $user->salt     = $salt;
        $user->pass     = $pass;
        $user->new_pass = true; //new format password?
        $user->mail     = $mail;
        $user->qq       = '';
        $user->rp       = 0.0;
        $user->vjb      = 0.0;
        $user->rank     = 0;
        $user->g        = $mail; //gravatar
        $user->gmd5     = md5($mail);
        $user->gender   = $data['gender'];
        $user->tlogin   = time();
        $user->iplogin  = '';
        $user->treg     = time();
        $user->ipreg    = $_SERVER['REMOTE_ADDR'];
        $user->sig      = '';
        $user->sigm     = '';
        $user->group    = GROUP_USER;
        $user->priv     = array('_' => null);
        $user->privacy  = array('_' => null);
        $user->stars    = array('_' => null);
        $user->pbms     = array(
            'pass'    => 0,
            'passlst' => array(),
            'ans'     => 0,
            'anslst'  => array(),
            'submit'  => 0
        );
        $user->settings = array(
            'first_reg' => true
        );
        $result         = $user->save();

        //Clear session

        if (!isset($options['no_session_checking'])) {

            $__SESSION->remove('reg-email');
            $__SESSION->remove('reg-code');

        }

        return $result;
    }

}