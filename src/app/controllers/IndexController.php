<?php

class IndexController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->setVars([
            'PAGE_CLASS' => 'home',
            'TITLE'      => gettext('Home'),
            'HEADLINE'   => gettext('Vijos')
        ]);
    }
}