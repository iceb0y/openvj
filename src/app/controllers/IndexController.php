<?php

class IndexController extends \VJ\Controller\Basic
{

    public function indexAction()
    {

        global $__CONFIG;

        if (
            $_SERVER['REQUEST_URI'] !== $__CONFIG->Misc->basePrefix.'/'
            && $_SERVER['REQUEST_URI'] !== $__CONFIG->Misc->basePrefix
        ) {
            return $this->raise404();
        }

        $this->view->setVars([
            'PAGE_CLASS' => 'home',
            'TITLE'      => gettext('Home')
        ]);
    }
}
