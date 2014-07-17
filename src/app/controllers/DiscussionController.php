<?php

use VJ\Controller\Basic;

class DiscussionController extends Basic
{
    public function commentAction()
    {
        \VJ\Security\CSRF::checkToken();
        \VJ\Validator::required($_POST, ['id', 'text']);
        $result = \VJ\Functions\Discussion::replyTopic($_POST['id'], $_POST['text']);

        return $this->forwardAjax($result);
    }
}
