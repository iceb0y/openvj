<?php

namespace VJ\Functions\Discussion;

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
     * @return array|string
     */
    public static function toTopic($topic_id, $content)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id = (string)$topic_id;
        $content  = (string)$content;

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

        $document      = self::createReplyDocument($content);
        $document['r'] = [];

        $mongo->Discussion->update(
            [
                '_id' => $topic_id
            ],
            [
                '$push' => [
                    'r' => $document
                ],
                '$set'  => [
                    'luser' => $_UID,
                    'ltime' => time()
                ],
                '$inc'  => [
                    'count'  => 1,
                    'countc' => 1
                ]
            ],
            ['upsert' => true]
        );

        return $document['_id'];

    }

    /**
     * 修改评论
     *
     * @param $topic_id
     * @param $comment_id
     * @param $content
     *
     * @return array|bool
     */
    public static function editComment($topic_id, $comment_id, $content)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id   = (string)$topic_id;
        $comment_id = (string)$comment_id;
        $content    = (string)$content;

        global $__CONFIG, $_UID;

        if (Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
        }

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            return I::error('NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record['r'] as $index => &$comment) {
            if ($comment['_id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            return I::error('NOT_FOUND', 'comment');
        }

        // has privilege?
        if ($_UID == $comment_target['uid']) {
            if (!$acl->has(PRIV_DISCUSSION_COMMENT_MODIFY_SELF)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_COMMENT_MODIFY_SELF');
            }
        } else {
            if (!$acl->has(PRIV_DISCUSSION_MODIFY_ANY)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_MODIFY_ANY');
            }
        }

        // modify
        $finder = 'r.'.$comment_index.'.';

        $mongo->Discussion->update(
            [
                '_id'         => $topic_id,
                $finder.'_id' => $comment_id
            ],
            [
                '$set' => self::createReplyModifySchema($content, $finder)
            ]
        );

        return true;
    }

    /**
     * 删除评论
     *
     * @param $topic_id
     * @param $comment_id
     *
     * @return array|bool
     */
    public static function deleteComment($topic_id, $comment_id)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id   = (string)$topic_id;
        $comment_id = (string)$comment_id;

        global $_UID;

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            return I::error('NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record['r'] as &$comment) {
            if ($comment['_id'] == $comment_id) {
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            return I::error('NOT_FOUND', 'comment');
        }

        // has privilege?
        if ($_UID == $comment_target['uid']) {
            if (!$acl->has(PRIV_DISCUSSION_COMMENT_DELETE_SELF)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_COMMENT_DELETE_SELF');
            }
        } else {
            if (!$acl->has(PRIV_DISCUSSION_DELETE_ANY)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_DELETE_ANY');
            }
        }

        // remove
        $mongo->Discussion->update(
            [
                '_id' => $topic_id
            ],
            [
                '$pull' => [
                    'r' => ['_id' => $comment_id]
                ],
                '$inc'  => [
                    'count'  => -1,
                    'countc' => -1
                ]
            ]
        );

        return true;
    }

    /**
     * 回复评论
     *
     * @param $topic_id
     * @param $comment_id
     * @param $content
     *
     * @return array|string
     */
    public static function toComment($topic_id, $comment_id, $content)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id   = (string)$topic_id;
        $comment_id = (string)$comment_id;
        $content    = (string)$content;

        global $__CONFIG, $_UID;

        if (strlen($topic_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'topic_id', 50);
        }

        if (!$acl->has(PRIV_DISCUSSION_REPLY_COMMENT)) {
            return I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_COMMENT');
        }

        if (Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
        }

        $document = self::createReplyDocument($content);

        $result = $mongo->Discussion->update(
            [
                '_id'   => $topic_id,
                'r._id' => $comment_id
            ],
            [
                '$push' => [
                    'r.$.r' => $document
                ],
                '$set'  => [
                    'luser' => $_UID,
                    'ltime' => time()
                ],
                '$inc'  => [
                    'count' => 1
                ]
            ]
        );

        if ($result['n'] == 0) {
            //no document found
            return I::error('NOT_FOUND', 'topic or comment');
        }

        return $document['_id'];

    }

    /**
     * 修改回复
     *
     * @param $topic_id
     * @param $comment_id
     * @param $reply_id
     * @param $content
     *
     * @return array|bool
     */
    public static function editReply($topic_id, $comment_id, $reply_id, $content)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id   = (string)$topic_id;
        $comment_id = (string)$comment_id;
        $reply_id   = (string)$reply_id;
        $content    = (string)$content;

        global $__CONFIG, $_UID;

        if (Utils::len($content) < $__CONFIG->Discussion->contentMin) {
            return I::error('CONTENT_TOOSHORT', $__CONFIG->Discussion->contentMin);
        }

        if (Utils::len($content) > $__CONFIG->Discussion->contentMax) {
            return I::error('CONTENT_TOOLONG', $__CONFIG->Discussion->contentMax);
        }

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            return I::error('NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record['r'] as $index => &$comment) {
            if ($comment['_id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            return I::error('NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        $reply_index  = -1;
        foreach ($comment_target['r'] as $index => &$reply) {
            if ($reply['_id'] == $reply_id) {
                $reply_index  = $index;
                $reply_target = & $reply;
            }
        }

        if ($reply_target == null) {
            return I::error('NOT_FOUND', 'reply');
        }

        // has privilege?
        if ($_UID == $reply_target['uid']) {
            if (!$acl->has(PRIV_DISCUSSION_REPLY_MODIFY_SELF)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_MODIFY_SELF');
            }
        } else {
            if (!$acl->has(PRIV_DISCUSSION_MODIFY_ANY)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_MODIFY_ANY');
            }
        }

        // modify
        $finder = 'r.'.$comment_index.'.r.'.$reply_index.'.';

        $mongo->Discussion->update(
            [
                '_id'         => $topic_id,
                $finder.'_id' => $reply_id
            ],
            [
                '$set' => self::createReplyModifySchema($content, $finder)
            ]
        );

        return true;
    }

    /**
     * 删除回复
     *
     * @param $topic_id
     * @param $comment_id
     * @param $reply_id
     *
     * @return array|bool
     */
    public static function deleteReply($topic_id, $comment_id, $reply_id)
    {

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $topic_id   = (string)$topic_id;
        $comment_id = (string)$comment_id;
        $reply_id   = (string)$reply_id;

        global $_UID;

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            return I::error('NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record['r'] as $index => &$comment) {
            if ($comment['_id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            return I::error('NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        foreach ($comment_target['r'] as &$reply) {
            if ($reply['_id'] == $reply_id) {
                $reply_target = & $reply;
            }
        }

        if ($reply_target == null) {
            return I::error('NOT_FOUND', 'reply');
        }

        // has privilege?
        if ($_UID == $reply_target['uid']) {
            if (!$acl->has(PRIV_DISCUSSION_REPLY_DELETE_SELF)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_REPLY_DELETE_SELF');
            }
        } else {
            if (!$acl->has(PRIV_DISCUSSION_DELETE_ANY)) {
                return I::error('NO_PRIV', 'PRIV_DISCUSSION_DELETE_ANY');
            }
        }

        // delete
        $mongo->Discussion->update(
            [
                '_id' => $topic_id
            ],
            [
                '$pull' => [
                    'r.'.$comment_index.'.r' => ['_id' => $reply_id]
                ],
                '$inc'  => [
                    'count' => -1
                ]
            ]
        );

        return true;
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
            'xtra' => new \stdClass(),

        ];

    }

    /**
     * 创建标准化回复更新文档
     *
     * @param        $markdownContent
     * @param string $keyPrefix
     *
     * @return array
     */
    private static function createReplyModifySchema($markdownContent, $keyPrefix = '')
    {

        global $_UID;

        return [

            $keyPrefix.'muid'  => $_UID,
            $keyPrefix.'mtime' => time(),
            $keyPrefix.'md'    => $markdownContent,
            $keyPrefix.'text'  => \VJ\Formatter\Markdown::parse($markdownContent)

        ];

    }

}