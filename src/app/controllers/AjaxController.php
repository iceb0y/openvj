<?php

use \VJ\I;

class AjaxController extends \Phalcon\Mvc\Controller
{

    public function rsaAction()
    {

        global $__CONFIG;

        $this->view->AJAX_DATA = [
            'key'       => $__CONFIG->RSA->public,
            'e'         => $__CONFIG->RSA->e,
            'timestamp' => time()
        ];

    }

    public function registerstep1Action()
    {

        $result = \VJ\Validator::required($_POST, ['mail']);

        if (\VJ\I::isError($result)) {
            $this->view->AJAX_DATA = $result;

            return;
        }

        $result                = \VJ\User\Account\Register::sendVerificationEmail($_POST['mail']);
        $this->view->AJAX_DATA = $result;

    }

    public function registerstep2Action()
    {

        $result = \VJ\Validator::required($_POST, ['user', 'pass', 'nick', 'gender', 'agreement', 'email', 'code']);

        if (\VJ\I::isError($result)) {
            $this->view->AJAX_DATA = $result;

            return;
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

        $this->view->AJAX_DATA = $result;

    }

    public function loginAction()
    {

        $result = \VJ\Validator::required($_POST, ['encrypted']);

        if (\VJ\I::isError($result)) {
            $this->view->AJAX_DATA = $result;

            return;
        }

        global $__CONFIG;

        // Decrypt data
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $rsa->loadKey($__CONFIG->RSA->private, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $s   = new Math_BigInteger($_POST['encrypted'], 16);
        $msg = $rsa->decrypt($s->toBytes());
        $msg = json_decode($msg, true);

        // Timestamp validation
        if (abs(time() - (int)$msg['timestamp']) > 10) {
            $result                = \VJ\I::error('EXPIRED');
            $this->view->AJAX_DATA = $result;

            return;
        }

        $result = \VJ\User\Account\Login::fromPassword($msg['user'], $msg['pass']);

        if (\VJ\I::isError($result)) {
            $this->view->AJAX_DATA = $result;

            return;
        }

        $result                = \VJ\User\Account\Login::user($result);
        $this->view->AJAX_DATA = $result;

    }
}