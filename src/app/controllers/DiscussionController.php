<?php

use \VJ\I;

class DiscussionController extends \VJ\Controller\Basic
{

    public function commentAction()
    {

        $result = \VJ\Security\CSRF::checkToken();

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $result = \VJ\Validator::required($_POST, ['id', 'text']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $result = \VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);

        return $this->forwardAjax($result);

    }

}