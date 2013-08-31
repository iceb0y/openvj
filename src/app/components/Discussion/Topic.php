<?php

namespace VJ\Discussion;

use \VJ\I;

class Topic
{

    const RECORDS_PER_PAGE = 80;

    /**
     * 获取一个讨论主题的基本信息
     *
     * @param $topic_id
     *
     * @return array
     */
    public static function initInfo($topic_id)
    {

        $mongo    = \Phalcon\DI::getDefault()->getShared('mongo');
        $topic_id = (string)$topic_id;

        $record = $mongo->findOne(
            ['_id' => $topic_id],
            ['r' => 0]
        );

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
     * 获取讨论评论内容
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
            return I::error('ARGUMENT_INVALID', 'page');
        }

        $record = $mongo->findOne(
            ['_id' => $topic_id],
            ['r' => ['$slice' => [$page * self::RECORDS_PER_PAGE, self::RECORDS_PER_PAGE]]]
        );

        if ($record == null) {
            return [];
        }

        return $record['r'];

    }


}