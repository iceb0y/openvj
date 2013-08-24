<?php

namespace VJ\Discussion;

use \VJ\I;
use \VJ\Utils;
use \VJ\User\Security\ACL;

class Reply
{

    /**
     * 发表评论
     *
     * @param $topic_id
     * @param $content
     *
     * @return array
     */
    public static function topic($topic_id, $content)
    {
        global $__CONFIG;

        if (strlen($topic_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!ACL::has(PRIV_DISCUSSION_COMMENT_TOPIC)) {
            return I::error('NO_PRIV', 'PRIV_DISCUSSION_COMMENT_TOPIC');
        }

        if (Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
        }


    }

    /**
     * 回复评论
     *
     * @param $topic_id
     * @param $comment_id
     * @param $content
     *
     * @return array
     */
    public static function comment($topic_id, $comment_id, $content)
    {

        if (strlen($topic_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!ACL::has(PRIV_DISCUSSION_REPLY_COMMENT)) {
            return I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_COMMENT');
        }

    }

}