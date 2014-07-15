<?php

use \VJ\I;

class AjaxController extends \VJ\Controller\Basic
{

    public function initialize()
    {

		try {
			if ($this->dispatcher->getActionName() !== 'general') {

				\VJ\Security\CSRF::checkToken();

			}
		} catch (\VJ\Ex $e) {
			return $this->raiseError(I::error($e->getArgs()));
		}

    }

    public function generalAction()
    {

        // This action should only be called from raiseError() / raise404()

        // Only accept forwarded calls
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
