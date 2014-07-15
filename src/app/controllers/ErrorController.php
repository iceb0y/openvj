<?php

use \VJ\I;
use \VJ\Utils;

class ErrorController extends \VJ\Controller\Basic
{

    public function initialize()
    {

        if (Utils::isAjax()) {

            if ($this->dispatcher->getActionName() == 'show404') {
                // TODO
                $this->view->ERROR_OBJECT = I::error('404');
            }

            $this->view->AJAX_DATA = $this->view->ERROR_OBJECT;

            $this->dispatcher->forward([
                'controller' => 'ajax',
                'action'     => 'general'
            ]);
        }

    }

    public function generalAction()
    {

        // Only accept forwarded calls
        if ($this->view->ERROR_OBJECT == null) {
            return $this->raise404();
        }

        $this->view->setVars([
            'PAGE_CLASS' => 'error',
            'TITLE'      => gettext('Error')
        ]);
    }

    public function show404Action()
    {

        $this->view->setVars([
            'PAGE_CLASS' => 'error_404',
            'TITLE'      => gettext('404')
        ]);

    }

}
