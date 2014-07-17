<?php

use VJ\Utils;
use VJ\Controller\Basic;

class ErrorController extends Basic
{

    public function initialize()
    {

        if (Utils::isAjax()) {

            if ($this->dispatcher->getActionName() == 'show404') {
                $this->view->EXCEPTION = new \VJ\Exception('ERR_404');
            }

            $this->view->AJAX_DATA = $this->view->EXCEPTION->toAjaxObject();

            $this->dispatcher->forward([
                'controller' => 'ajax',
                'action'     => 'general'
            ]);
        }
    }

    public function generalAction($exception = null)
    {

        // Only accept forwarded calls
        if ($this->view->EXCEPTION == null) {
            return $this->raise404();
        }

        $this->view->ERROR_OBJECT = $this->view->EXCEPTION->toAjaxObject();

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
