<?php

use \VJ\I;

class AjaxController extends \VJ\Controller\Basic
{
    public function initialize()
    {
        if ($this->dispatcher->getActionName() !== 'general') {
            \VJ\Security\CSRF::checkToken();
        }
    }

    public function generalAction()
    {
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