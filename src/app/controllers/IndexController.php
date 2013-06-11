<?php

class IndexController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->setVars(array(
            'PAGE_CLASS' => 'home',
            'TITLE'      => gettext('Home'),
            'HEADLINE'   => gettext('Vijos')
        ));
    }
}