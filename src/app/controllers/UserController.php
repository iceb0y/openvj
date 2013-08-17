<?php

class UserController extends \Phalcon\Mvc\Controller
{

    public function registerAction()
    {
        $this->view->setVars([
            'PAGE_CLASS' => 'user_reg',
            'TITLE'      => gettext('Register'),
            'STEP'       => 1
        ]);
    }
}