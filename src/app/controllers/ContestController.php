<?php

class ContestController extends \VJ\Controller\Basic
{
    public function IndexAction()
    {
        global $__CONFIG;

        $this->view->setVars([
            'PAGE_CLASS' => 'home',
            'TITLE'      => gettext('Home')
        ]);
    }
}
