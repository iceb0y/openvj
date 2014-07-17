<?php

namespace VJ\Functions;

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
        if (is_array($topic_id) || $topic_id == null) {
            $record = $topic_id;
        } else {
            $mongo    = \Phalcon\DI::getDefault()->getShared('mongo');
            $topic_id = (string)$topic_id;

            $record = $mongo->Discussion->findOne(
                ['_id' => $topic_id],
                ['r' => 0]
            );
        }

        if ($record == null) {
            return [
                'count_all'     => 0,
                'count_comment' => 0,
                'pages'         => 0,
                'exist'         => false
            ];
        }

        $pages = ceil($record['countc'] / self::RECORDS_PER_PAGE);

        return [
            'count_all'     => $record['count'],
            'count_comment' => $record['countc'],
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
        $mongo    = \Phalcon\DI::getDefault()->getShared('mongo');
        $topic_id = (string)$topic_id;
        $page     = (int)$page;

        if ($page < 0) {
            throw new \VJ\Exception('ERR_ARGUMENT_INVALID', 'page');
        }

        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id],
            [
                'r'      => ['$slice' => [$page * self::RECORDS_PER_PAGE, self::RECORDS_PER_PAGE]],
                'count'  => 1,
                'countc' => 1
            ]
        );

        $result = [
            'id'      => $topic_id,
            'info'    => self::getInfo($record),
            'comment' => []
        ];

        if ($record != null) {
            $result['comment'] = $record['r'];
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
        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

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
     * 获取评论原始内容
     *
     * @param $topic_id
     * @param $comment_id
     *
     * @return array|string
     */
    public static function getCommentContent($topic_id, $comment_id)
    {
        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim'
        ]);

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record['r'] as &$comment) {
            if ($comment['_id'] == $comment_id) {
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
        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

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
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
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
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // has privilege?
        if ($_UID == $comment_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_COMMENT_MODIFY_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_MODIFY_ANY');
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
        $mongo = $di->getShared('mongo');

        global $_UID;

        $argv = [
            'topic_id'   => &$topic_id,
            'comment_id' => &$comment_id
        ];

        \VJ\Validator::filter($argv, [
            'topic_id'   => 'trim',
            'comment_id' => 'trim'
        ]);

        // Get the comment
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record['r'] as &$comment) {
            if ($comment['_id'] == $comment_id) {
                $comment_target = & $comment;
                break;
            }
        }

        if ($comment_target == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // has privilege?
        if ($_UID == $comment_target['uid']) {
            \VJ\User\ACL::check('PRIV_DISCUSSION_COMMENT_DELETE_SELF');
        } else {
            \VJ\User\ACL::check('PRIV_DISCUSSION_DELETE_ANY');
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
        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

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
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic or comment');
        }

        return $document['_id'];
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
        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

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
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
        }

        $comment_target = null;
        foreach ($record['r'] as &$comment) {
            if ($comment['_id'] == $comment_id) {
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
            if ($reply['_id'] == $reply_id) {
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
        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

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
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
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
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
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
        $mongo = $di->getShared('mongo');

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
        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id]
        );

        if ($record == null) {
            throw new \VJ\Exception('ERR_NOT_FOUND', 'topic');
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
            throw new \VJ\Exception('ERR_NOT_FOUND', 'comment');
        }

        // Get the reply
        $reply_target = null;
        foreach ($comment_target['r'] as &$reply) {
            if ($reply['_id'] == $reply_id) {
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

            '_id'  => uniqid(),
            'uid'  => $_UID,
            'time' => time(),
            'md'   => new \MongoBinData(gzcompress($markdownContent)),
            'text' => \VJ\Formatter\Markdown::parse($markdownContent),
            'xtra' => new \stdClass(),

        ];

        $doc['vote_id'] = 'dcz_'.$doc['_id'];

        return $doc;
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
            $keyPrefix.'md'    => new \MongoBinData(gzcompress($markdownContent)),
            $keyPrefix.'text'  => \VJ\Formatter\Markdown::parse($markdownContent)

        ];
    }
}
