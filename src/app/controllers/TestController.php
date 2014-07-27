<?php

use VJ\Models;

class TestController extends \Phalcon\Mvc\Controller {

    public function indexAction() {



    }

    public function loginAction() {

        $this->view->setVar('result',"abc");

        if ($this->request->isPost()==true) {

            \VJ\Security\CSRF::checkToken();
            \VJ\Validator::required($_POST, ['user', 'pass']);

            $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);
            $result = \VJ\User\Account\Login::user($result);
            
            // $this->view->setVar('result',$_POST['user']);

            $this->view->setVar('result','Success');

        }

        // $this->view->setVar('result',$_POST['user']);
        // $this->view->setVar('result',$this->request->getPost['user']);

        // global $dm;

        // $results=$dm->getRepository('VJ\Models\User_T')->findBy(array('name'  =>  'sweet'));

        // $this->view->setVar('result',count($results));

        // \VJ\Security\CSRF::checkToken();
        // \VJ\Validator::required($_POST, ['user', 'pass']);

        // $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);
        // $result = \VJ\User\Account\Login::user($result);

        // return $this->forwardAjax($result);

    }

    public function registerAction() {

        $this->view->setVar('result',0);

        $request=$this->request;

        if ($request->isPost()) {

            \VJ\Security\CSRF::checkToken();

            \VJ\Validator::required($_POST, ['user', 'pass', 'nick', 'gender', 'agreement', 'email', 'code']);
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


            // \VJ\Git\Repository::create('uid_'.$result['uid']);

            // $result = \VJ\User\Account\Login::fromPassword($_POST['user'], $_POST['pass']);
            // $result = \VJ\User\Account\Login::user($result);

            // $this->view->setVar('result',$uid);
            $this->view->setVar('result',$result['uid']);

        }

    }

}