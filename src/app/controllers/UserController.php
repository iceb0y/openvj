<?php

class UserController extends \Phalcon\Mvc\Controller
{

    public function registerAction()
    {
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