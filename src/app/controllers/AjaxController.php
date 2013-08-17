<?php

class AjaxController extends \Phalcon\Mvc\Controller
{

    public function rsaAction()
    {

        global $__CONFIG;

        $this->view->setVars(['AJAX_DATA' => [
            'key'       => $__CONFIG->RSA->public,
            'e'         => $__CONFIG->RSA->e,
            'timestamp' => time()
        ]]);
    }

    public function registerstep1Action()
    {

        $result = \VJ\User\Account\Register::sendVerificationEmail($_POST['mail']);

        $this->view->setVars(['AJAX_DATA' => [
            'result' => $result
        ]]);
        
    }

    public function loginAction()
    {

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

            $ret = \VJ\I::error('EXPIRED');

        } else {

            $ret = \VJ\User\Account\Login::fromPassword($msg['user'], $msg['pass']);

            if (!\VJ\I::isError($ret)) {

                $ret = \VJ\User\Account\Login::user($ret);

            }

        }

        $this->view->setVars(['AJAX_DATA' => $ret]);

    }
}