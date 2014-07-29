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
            $this->view->setVar('result','Success');

            $result=\VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);
            // $result=\VJ\Formatter\Markdown::parse('<h1>fewfewfe</h1>');

            // $this->view->setVar('result',$result->text);
        }

    }

    public function chakanAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {

            $record=\VJ\Functions\Discussion::get($_POST['id']);

            $this->view->setVar('result','Success');

            $c=count($record['comment']);


            // $c=gettype($record['comment']);
            // $c='';
            foreach ($record['comment'] as $key) {
                $c=$c.$key['text'];
            }

            $this->view->setVar('result',$c);
            // $this->view->setVar('result',$_POST['id']);


        }

    }

}