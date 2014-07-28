<?php

use VJ\Models;


class TestController extends \Phalcon\Mvc\Controller {

    public function indexAction() {



    }

    public function tijiaoAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {
            \VJ\Security\CSRF::checkToken();
            \VJ\Validator::required($_POST, ['id', 'text']);
            \VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);
            $this->view->setVar('result','Success');
        }

    }

    public function chakanAction() {

        $this->view->setVar('result','');

    }

}