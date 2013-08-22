<?php

class TypographyController extends \VJ\Controller\Basic
{

    public function indexAction()
    {
        $this->view->setVars([
            'PAGE_CLASS' => 'typography',
            'TITLE'      => gettext('Typography')
        ]);
    }
}