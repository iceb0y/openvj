<?php

use VJ\Controller\Basic;

class AjaxController extends Basic
{
    public function initialize()
    {
        if ($this->dispatcher->getActionName() !== 'general') {
            \VJ\Security\CSRF::checkToken();
        }
    }

    public function generalAction()
    {
        $this->response->setHeader("Content-Type", "application/json");

        if ($this->view->AJAX_DATA === null) {
            return $this->raise404();
        }
    }

    public function forwardedAction()
    {
        // This action checks CSRF-token (NOTE: after code execution)
        // Only accept forwarded calls
        if ($this->view->AJAX_DATA === null) {
            return $this->raise404();
        }
    }
}