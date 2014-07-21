<?php

class TestController extends \Phalcon\Mvc\Controller {

    public function indexAction() {



    }

    public function loginAction() {


        $this->view->setVar('result','NULL');

    }

    public function registerAction() {

        $this->view->setVar('result','NULL');

    }

}