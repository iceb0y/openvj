<?php

class UserController extends \Phalcon\Mvc\Controller
{

    public function registerAction()
    {
        $this->view->setVars(array(
            'PAGE_CLASS' => 'user_reg',
            'TITLE'      => gettext('Register'),
            'HEADLINE'   => gettext('Register - Validation')
        ));
    }
}