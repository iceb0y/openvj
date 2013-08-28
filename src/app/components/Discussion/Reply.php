<?php

namespace VJ\Discussion;

use \VJ\I;
use \VJ\Utils;

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

        $di = \Phalcon\DI::getDefault();
        $acl = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id = (string)$topic_id;
        $content = (string)$content;

        global $__CONFIG, $_UID;

        if (strlen($topic_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!$acl->has(PRIV_DISCUSSION_COMMENT_TOPIC)) {
            return I::error('NO_PRIV', 'PRIV_DISCUSSION_COMMENT_TOPIC');
        }

        if (Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
        }

        $document = self::createReplyDocument($content);
        $document['r'] = [];

        $update = [
            'luser' => $_UID,
            'ltime' => time(),
            'count' => ['$inc' => 1],
            'r'     => ['$push' => $document]
        ];

        $mongo->Discussion->update(['_id' => $topic_id], $update, ['upsert' => true]);

        return $document['_id'];

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

        $di = \Phalcon\DI::getDefault();
        $acl = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id = (string)$topic_id;
        $comment_id = (string)$comment_id;
        $content = (string)$content;

        global $__CONFIG, $_UID;

        if (strlen($topic_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!$acl->has(PRIV_DISCUSSION_REPLY_COMMENT)) {
            return I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_COMMENT');
        }

        $document = self::createReplyDocument($content);

        $result = $mongo->Discussion->update(
            [
                '_id' => $topic_id,
                'r._id' => $comment_id
            ],
            [
                '$push' => ['r.$.r' => $document]
            ]
        );

        if ($result['n'] == 0) {
            //no document found
            return I::error('NOT_FOUND', 'discussion or topic');
        }

        return $document['_id'];

    }

    /**
     * 创建标准化回复文档
     *
     * @param $markdownContent
     *
     * @return array
     */
    private static function createReplyDocument($markdownContent)
    {

        global $_UID;

        return [

            '_id'  => uniqid(),
            'uid'  => $_UID,
            'time' => time(),
            'md'   => $markdownContent,
            'text' => \VJ\Formatter\Markdown::parse($markdownContent),
            'vote' => ['sp' => new \stdClass(), 'ob' => new \stdClass()],
            'xtra' => new \stdClass(),

        ];

    }

}