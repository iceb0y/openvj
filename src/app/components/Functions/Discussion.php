<?php

namespace VJ\Functions;

use VJ\Models;

class Discussion
{

    const RECORDS_PER_PAGE = 50;

    /**
     * 获取一个讨论主题的基本信息
     *
     * @param $topic_id
     *
     * @return array
     */
    public static function getInfo($topic_id)
    {
        if (isset($topic_id->id) || $topic_id == null) {
            $record = $topic_id;
        } else {

            global $dm;

            $topic_id = (string)$topic_id;

            $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);
        }

        if ($record == null) {
            return [
                'count_all'     => 0,
                'count_comment' => 0,
                'pages'         => 0,
                'exist'         => false
            ];
        }

        $pages = ceil($record->countc / self::RECORDS_PER_PAGE);

        return [
            'count_all'     => $record->count,
            'count_comment' => $record->countc,
            'pages'         => $pages,
            'exist'         => true
        ];
    }

    /**
     * 获取讨论的评论内容和基本信息
     *
     * @param     $topic_id
     * @param int $page
     *
     * @return array
     */
    public static function get($topic_id, $page = 0)
    {
        global $dm;
        $topic_id = (string)$topic_id;
        $page     = (int)$page;

        if ($page < 0) {
            throw new \VJ\Exception('ERR_ARGUMENT_INVALID', 'page');
        }

        $record = $dm->createQueryBuilder('VJ\Models\Discussion')
            ->select('r', 'count', 'countc')
            ->field('id')->equals($topic_id)
            ->getQuery()
            ->getSingleResult();

        $record->r = array_slice($record->r, $page * self::RECORDS_PER_PAGE, self::RECORDS_PER_PAGE);

        $result = [
            'id'      => $topic_id,
            'info'    => self::getInfo($record),
            'comment' => []
        ];

        if ($record != null) {
            $result['comment'] = $record->r;
        }

        return $result;
    }

    /**
     * 发表评论
     *
     * @param $topic_id
     * @param $content
     *
     * @return array|string
     */
    public static function replyTopic($topic_id, $content)
    {

        global $dm;

        global $__CONFIG, $_UID;

        \VJ\User\ACL::check('PRIV_DISCUSSION_COMMENT_TOPIC');

        $argv = [
            'topic_id' => &$topic_id,
            'content'  => &$content
        ];

        \VJ\Validator::filter($argv, [
            'topic_id' => 'trim',
            'content'  => 'trim'
        ]);

        \VJ\Validator::validate($argv, [
            'topic_id' => [
                'length' => [0, 50]
            ],
            'content'  => [
                'contentlength' => [$__CONFIG->Discussion->contentMin, $__CONFIG->Discussion->contentMax]
            ]
        ]);

        $document      = & self::createReplyDocument($content);
        $document['r'] = [];

        $dm->createQueryBuilder('VJ\Models\Discussion')
            ->update()
            ->upsert(true)
            ->field('id')->equals($topic_id)
            ->field('r')->push($document)
            ->field('luser')->set(time())
            ->field('count')->inc(1)
            ->field('countc')->inc(1)
            ->getQuery()
            ->execute();

        return $document['id'];
    }

    /**
     * 获取评论原始内容
     *
     * @param $topic_id
     * @param $comment_id
     *
     * @return array|string
     */
    public static function getCommentContent($topic_id, $comment_id)
    {
        global $dm;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim'
        ]);

        // Get the comment

        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record['r'] as &$comment) {
            if ($comment['id'] == $comment_id) {
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        return gzuncompress($comment_target['md']);
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
        global $dm;

        global $__CONFIG, $_UID;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id,
            'content'    => &$content
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim',
            'content'    => 'trim'
        ]);

        \VJ\Validator::validate($argv, [
            'content' => [
                'contentlength' => [$__CONFIG->Discussion->contentMin, $__CONFIG->Discussion->contentMax]
            ]
        ]);

        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record->r as $index => $comment) {
            if ($comment['id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // has privilege?
        if ($_UID == $comment_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_COMMENT_MODIFY_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_MODIFY_ANY');
        }

        // // modify
        $finder = 'r.'.$comment_index.'.';

        $dm->createQueryBuilder('VJ\Models\Discussion')
            ->update()
            ->field('id')->equals($topic_id)
            ->field($finder.'muid')->set($_UID)
            ->field($finder.'mtime')->set(time())
            ->field($finder.'md')->set(new \MongoBinData(gzcompress($content)))
            ->field($finder.'text')->set(\VJ\Formatter\Markdown::parse($content))
            ->getQuery()
            ->execute();

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

        global $_UID;

        global $dm;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim'
        ]);

        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record->r as &$comment) {
            if ($comment['id'] == $comment_id) {
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        has privilege ?
        if ($_UID == $comment_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_COMMENT_DELETE_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_DELETE_ANY');
        }

        // remove
        $dm->createQueryBuilder('VJ\Models\Discussion')
            ->update()
            ->field('id')->equals($topic_id)
            ->field('r')->pull(['id' => $comment_id])
            ->field('count')->inc(-1)
            ->field('countc')->inc(-1)
            ->getQuery()
            ->execute();

        // delete votes
        foreach ($comment_target['r'] as $reply) {
            \VJ\Functions\Vote::_deleteEntity($reply['vote_id']);
        }

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
    public static function replyComment($topic_id, $comment_id, $content)
    {
        global $dm;

        global $__CONFIG, $_UID;

        \VJ\User\ACL::check('PRIV_DISCUSSION_REPLY_COMMENT');

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id,
            'content'    => &$content
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim',
            'content'    => 'trim'
        ]);

        \VJ\Validator::validate($argv, [
            'topic_id' => [
                'length' => [0, 50]
            ],
            'content'  => [
                'contentlength' => [$__CONFIG->Discussion->contentMin, $__CONFIG->Discussion->contentMax]
            ]
        ]);


        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record->r as $index => &$comment) {
            if ($comment['id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        //Reply The Comment
        $document = self::createReplyDocument($content);

        $resullt = $dm->createQueryBuilder('VJ\Models\Discussion')
            ->findAndUpdate()
            ->field('id')->equals($topic_id)
            ->field('r.'.$comment_index.'r')->push($document)
            ->field('luser')->set($_UID)
            ->field('ltime')->set(time())
            ->field('count')->inc(1)
            ->getQuery()
            ->execute();

        if (count($result) == 0) {
            //no document found
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic or comment');
        }

        return $document['id'];
    }

    /**
     * 获取回复原始内容
     *
     * @param $topic_id
     * @param $comment_id
     * @param $reply_id
     *
     * @return array|string
     */
    public static function getReplyContent($topic_id, $comment_id, $reply_id)
    {

        global $dm;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id,
            'reply_id'   => &$reply_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim',
            'reply_id'   => 'trim'
        ]);

        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record->r as &$comment) {
            if ($comment['id'] == $comment_id) {
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        foreach ($comment_target['r'] as &$reply) {
            if ($reply['id'] == $reply_id) {
                $reply_target = & $reply;
            }
        }

        if ($reply_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'reply');
        }

        return gzuncompress($reply_target['md']);
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
        global $dm;

        global $__CONFIG, $_UID;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id,
            'reply_id'   => &$reply_id,
            'content'    => &$content
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim',
            'reply_id'   => 'trim',
            'content'    => 'trim'
        ]);

        \VJ\Validator::validate($argv, [
            'content' => [
                'contentlength' => [$__CONFIG->Discussion->contentMin, $__CONFIG->Discussion->contentMax]
            ]
        ]);

        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record->r as $index => $comment) {
            if ($comment['id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        $reply_index  = -1;
        foreach ($comment_target['r'] as $index => &$reply) {
            if ($reply['id'] == $reply_id) {
                $reply_index  = $index;
                $reply_target = & $reply;
            }
        }

        if ($reply_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'reply');
        }

        // has privilege?
        if ($_UID == $reply_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_REPLY_MODIFY_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_MODIFY_ANY');
        }

        // modify
        $finder = 'r.'.$comment_index.'.r.'.$reply_index.'.';

        $dm->createQueryBuilder('VJ\Models\Discussion')
            ->update()
            ->field('id')->equals($topic_id)
            ->field($finder.'muid')->set($_UID)
            ->field($finder.'mtime')->set(time())
            ->field($finder.'md')->set(new \MongoBinData(gzcompress($content)))
            ->field($finder.'text')->set(\VJ\Formatter\Markdown::parse($content))
            ->getQuery()
            ->execute();

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

        global $dm;

        global $_UID;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id,
            'reply_id'   => &$reply_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim',
            'reply_id'   => 'trim'
        ]);

        // Get the comment
        $record = $dm->getRepository('VJ\Models\Discussion')->findOneBy(['id' => $topic_id]);

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        $comment_index  = -1;
        foreach ($record->r as $index => $comment) {
            if ($comment['id'] == $comment_id) {
                $comment_index  = $index;
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        foreach ($comment_target['r'] as &$reply) {
            if ($reply['id'] == $reply_id) {
                $reply_target = & $reply;
            }
        }

        if ($reply_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'reply');
        }

        // has privilege?
        if ($_UID == $reply_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_REPLY_DELETE_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_DELETE_ANY');
        }

        // delete
        $dm->createQueryBuilder('VJ\Models\Discussion')
            ->update()
            ->field('id')->equals($topic_id)
            ->field('r.'.$comment_index.'.r')->pull(['id' => $reply_id])
            ->field('count')->inc(-1)
            ->getQuery()
            ->execute();

        // delete votes
        \VJ\Functions\Vote::_deleteEntity($reply_target['vote_id']);

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

        $doc = [
            'id'   => uniqid(),
            'uid'  => $_UID,
            'time' => time(),
            'md'   => new \MongoBinData(gzcompress($markdownContent)),
            'text' => \VJ\Formatter\Markdown::parse($markdownContent),
            'xtra' => new \stdClass(),
        ];

        $doc['vote_id'] = 'dcz_'.$doc['id'];

        return $doc;
    }
}