<?php

namespace VJ\Discussion;

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
            return \VJ\I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!\VJ\User\Security\Privilege::has(PRIV_DISCUSSION_REPLY_TOPIC)) {
            return \VJ\I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_TOPIC');
        }

        if (\VJ\Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return \VJ\I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (\VJ\Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return \VJ\I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
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
            return \VJ\I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!\VJ\User\Security\Privilege::has(PRIV_DISCUSSION_REPLY_COMMENT)) {
            return \VJ\I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_COMMENT');
        }

    }

}