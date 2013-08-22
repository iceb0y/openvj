<?php

class IndexController extends \VJ\Controller\Basic
{

    public function indexAction()
    {
        $this->view->setVars([
            'PAGE_CLASS' => 'home',
            'TITLE'      => gettext('Home')
        ]);
    }
}