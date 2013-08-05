<?php

class TypographyController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->setVars(array(
            'PAGE_CLASS' => 'typography',
            'TITLE'      => gettext('Typography'),
            'HEADLINE'   => gettext('Typography')
        ));
    }
}