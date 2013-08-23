<?php

class ManageController extends \VJ\Controller\Basic
{

    public function initialize()
    {

        $this->view->STYLE_WIDE = true;

    }

    public function indexAction()
    {

        // Check privilege

        $this->view->setVars([
            'PAGE_CLASS' => 'manage_index',
            'TITLE'      => gettext('Manage')
        ]);

    }

}