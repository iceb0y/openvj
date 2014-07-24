<?php

use VJ\Models;

class TestController extends \Phalcon\Mvc\Controller {

    public function indexAction() {



    }

    public function loginAction() {

        // global $dm;

        // $results=$dm->getRepository('VJ\Models\User_T')->findBy(array('name'  =>  'sweet'));

        // $this->view->setVar('result',count($results));

    }

    public function registerAction() {

        // global $dm;

        // $TT=new Models\User_T();

        // $TT->name='sweet';

        // $this->view->setVar('result',$TT->name);

        // $dm->persist($TT);

        // $dm->flush();

    }

}