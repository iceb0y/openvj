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

            $record=\VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);

            $this->view->setVar('result',$record);
        }

    }

    public function chakanAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {

            $record=\VJ\Functions\Discussion::get($_POST['id']);

            $this->view->setVar('result','Success');

            global $dm;

            $c='';

            foreach ($record['comment'] as $r) {
                $c=$c.$r['text'];
            }      

            $this->view->setVar('result',$c);

        }

    }

    public function topicAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {

            $result=\VJ\Functions\Topic::create($_POST['title'],$_POST['content'],'');

            $this->view->setVar('result',$result);
        }

    }

    public function editAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {

            $result=\VJ\Functions\Discussion::editComment($_POST['topic_id'], $_POST['comment_id'], $_POST['content']);

            $this->view->setVar('result',$result);
        }

    }

    public function deleteAction() {

        $this->view->setVar('result','');

        if ($this->request->isPost()) {

            $result=\VJ\Functions\Discussion::deleteComment($_POST['topic_id'], $_POST['comment_id']);

            $this->view->setVar('result','Success');
        }
        
    }

}