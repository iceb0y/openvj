<?php

use \VJ\I;

class UserController extends \VJ\Controller\Basic
{

    public function registerAction()
    {

        if ($this->request->isPost() === true) {

            // Check TOKENs

            $result = \VJ\Security\CSRF::checkToken();

            if (I::isError($result)) {
                return $this->raiseError($result);
            }

            if (!isset($_POST['user'])) {

                // STEP1: Mail validation

                $result = \VJ\Validator::required($_POST, ['email']);

                if (I::isError($result)) {
                    return $this->raiseError($result);
                }

                $result = \VJ\User\Account\Register::sendVerificationEmail($_POST['email']);

                return $this->forwardAjax($result);


            } else {

                // STEP2: Sign up

                $result = \VJ\Validator::required(
                    $_POST,
                    ['user', 'pass', 'nick', 'gender', 'agreement', 'email', 'code']
                );

                if (I::isError($result)) {
                    return $this->raiseError($result);
                }

                $result = \VJ\User\Account\Register::register(
                    $_POST['user'],
                    $_POST['pass'],
                    $_POST['nick'],
                    $_POST['gender'],
                    $_POST['agreement'],
                    [
                        'email' => $_POST['email'],
                        'code'  => $_POST['code']
                    ]
                );

                if (I::isError($result)) {
                    return $this->raiseError($result);
                }

                // Prepare Git repository
                \VJ\Git\Repository::create('uid_'.$result['uid']);

                // Log in immediately
                $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);

                if (I::isError($result)) {
                    return $this->raiseError($result);
                }

                $result = \VJ\User\Account\Login::user($result);

                return $this->forwardAjax($result);

            }

        } else {

            $this->view->setVars([
                'PAGE_CLASS' => 'user_reg',
                'TITLE'      => gettext('Register')
            ]);

            if (isset($_GET['code']) && isset($_GET['email'])) {

                $result = \VJ\User\Account\Register::verificateEmail($_GET['email'], $_GET['code']);

                if (\VJ\I::isError($result)) {
                    $this->view->setVar('STEP', 0);
                    $this->view->setVar('ERROR', $result['errorMsg']);
                } else {
                    $this->view->setVar('REG_MAIL', $result['mail']);
                    $this->view->setVar('STEP', 2);
                    $this->view->setVar('REG_PARAM', $result);
                }

            } else {

                $this->view->setVar('STEP', 1);

            }

        }

    }

    public function helloAction()
    {

        $acl = \Phalcon\DI::getDefault()->getShared('acl');

        if (!$acl->has(PRIV_USER_MODIFY_SETTINGS)) {
            return $this->raiseError('NO_PRIV', 'PRIV_USER_MODIFY_SETTINGS');
        }

        $this->view->setVars([
            'PAGE_CLASS' => 'user_hello',
            'TITLE'      => gettext('Hello')
        ]);

    }

    public function loginAction()
    {

        $result = \VJ\Security\CSRF::checkToken();

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $result = \VJ\Validator::required($_POST, ['encrypted']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        global $__CONFIG;

        // Decrypt data
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $rsa->loadKey($__CONFIG->RSA->private, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $s   = new Math_BigInteger($_POST['encrypted'], 16);
        $msg = $rsa->decrypt($s->toBytes());
        $msg = json_decode($msg, true);

        // Timestamp validation: accpet 10s delay
        if (abs(time() - (int)$msg['timestamp']) > 10) {
            return $this->raiseError('EXPIRED');
        }

        $result = \VJ\User\Account\Login::fromPassword($msg['user'], $msg['pass']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $result = \VJ\User\Account\Login::user($result);

        return $this->forwardAjax($result);

    }

}