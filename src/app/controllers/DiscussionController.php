<?php

use VJ\I;
use VJ\Discussion;

class DiscussionController extends \VJ\Controller\Basic
{

    public function initAction()
    {

        $result = \VJ\Validator::required($_GET, ['topicid']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $info = Discussion\Topic::initInfo($_GET['topicid']);

        if ($info['count_all'] > 0) {
            $r = Discussion\Topic::get($_GET['topicid'], 0);
        } else {
            $r = [];
        }

        $result = [
            'info' => $info,
            'r'    => $r
        ];

        return $this->forwardAjax($result);

    }

    public function getAction()
    {

        $result = \VJ\Validator::required($_POST, ['topicid', 'page']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        $result = Discussion\Topic::get($_GET['topicid'], $_GET['page']);

        if (I::isError($result)) {
            return $this->raiseError($result);
        }

        return $this->forwardAjax($result);

    }

}