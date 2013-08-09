<?php

class AjaxController extends \Phalcon\Mvc\Controller
{

    public function rsaAction()
    {

        global $config;

        $this->view->setVars(array('AJAX_DATA' => array(
            'key'       => $config->RSA->public,
            'e'         => $config->RSA->e,
            'timestamp' => time()
        )));
    }

    public function loginAction()
    {

        global $config;

        // Decrypt data
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $rsa->loadKey($config->RSA->private, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $s = new Math_BigInteger($_POST['encrypted'], 16);
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

        $this->view->setVars(array('AJAX_DATA' => $ret));

    }
}