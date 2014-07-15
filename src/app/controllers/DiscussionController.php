<?php

use \VJ\I;

class DiscussionController extends \VJ\Controller\Basic
{

    public function commentAction()
    {

		try {

			\VJ\Security\CSRF::checkToken();

			\VJ\Validator::required($_POST, ['id', 'text']);

			$result = \VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);

			return $this->forwardAjax($result);
		} catch (\VJ\Ex $e) {
			$this->raiseError(I::error($e->getArgs()));
		}

	}
}
