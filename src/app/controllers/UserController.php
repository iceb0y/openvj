<?php

use \VJ\I;

class UserController extends \VJ\Controller\Basic
{

    public function registerAction()
    {

        if ($this->request->isPost() === true) {

            // Check TOKENs

            \VJ\Security\CSRF::checkToken();

            if (!isset($_POST['user'])) {

                // STEP1: Mail validation

                \VJ\Validator::required($_POST, ['email']);

                $result = \VJ\User\Account\Register::sendVerificationEmail($_POST['email']);

                return $this->forwardAjax($result);


            } else {

                // STEP2: Sign up

                \VJ\Validator::required(
                    $_POST,
                    ['user', 'pass', 'nick', 'gender', 'agreement', 'email', 'code']
                );

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

                // Prepare Git repository
                \VJ\Git\Repository::create('uid_'.$result['uid']);

                // Log in immediately
                $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);

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

//              if (\VJ\I::isError($result)) {
//                  $this->view->setVar('STEP', 0);
//                  $this->view->setVar('ERROR', $result['errorMsg']);
//              } else {
                $this->view->setVar('REG_MAIL', $result['mail']);
                $this->view->setVar('STEP', 2);
                $this->view->setVar('REG_PARAM', $result);
//              }

            } else {

                $this->view->setVar('STEP', 1);

            }

        }
    
    }

    public function helloAction()
    {

        $acl = \Phalcon\DI::getDefault()->getShared('acl');

        \VJ\User\ACL::check('PRIV_USER_MODIFY_SETTINGS');

        $this->view->setVars([
            'PAGE_CLASS' => 'user_hello',
            'TITLE'      => gettext('Hello')
        ]);

    }

    public function loginAction()
    {

        \VJ\Security\CSRF::checkToken();

        \VJ\Validator::required($_POST, ['user', 'pass']);

        $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);

        $result = \VJ\User\Account\Login::user($result);

        return $this->forwardAjax($result);

    }

}
