<?php

class ManageController extends \VJ\Controller\Basic
{

    public function initialize()
    {

        // TODO: Check privilege

    }

    public function indexAction()
    {

        // TODO: Check privilege

        $this->view->setVars([
            'PAGE_CLASS' => 'manage_index page_manage',
            'TITLE'      => gettext('Manage')
        ]);

    }

}