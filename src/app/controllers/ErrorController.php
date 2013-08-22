<?php

use \VJ\I;

class ErrorController extends \Phalcon\Mvc\Controller
{

    public function show404Action()
    {
        $this->view->setVars([
            'PAGE_CLASS' => 'error_404',
            'TITLE'      => gettext('404')
        ]);
    }

}