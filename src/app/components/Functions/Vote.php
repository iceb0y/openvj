<?php

namespace VJ\Functions;

use \VJ\I;

class Vote
{

    const ATTITUDE_UP   = 0;
    const ATTITUDE_DOWN = 1;

    const _QUERY_MAX_CHUNK = 30;

    private static $emptyVoteModel = ['up_count' => 0, 'down_count' => 0];

    /**
     * 获取评价内容
     *
     * @param int $vote_id
     *
     * @return array
     */
    public static function get($vote_id)
    {

        $vote_id = (string)$vote_id;
        $mongo   = \Phalcon\DI::getDefault()->getShared('mongo');

        $record = $mongo->Vote->findOne(
            ['_id' => $vote_id],
            ['upc' => 1, 'dnc' => 1]
        );

        $result = self::$emptyVoteModel;

        if ($record != null) {
            $result['up_count']   = $record['upc'];
            $result['down_count'] = $record['dnc'];
        }

        return $result;

    }

    /**
     * 批量获取评价内容
     *
     * @param $vidList
     *
     * @return array
     */
    public static function getArray($vidList)
    {

        $vidList  = array_map('strval', $vidList);
        $vidLists = array_chunk($vidList, self::_QUERY_MAX_CHUNK);

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $result = [];

        // Separate into many chunks.
        foreach ($vidLists as $list) {

            $cursor = $mongo->Vote->find(
                ['_id' => ['$in' => $list]],
                ['upc' => 1, 'dnc' => 1]
            );

            foreach ($cursor as $vote) {
                $result[$vote['_id']] = [
                    'up_count'   => $vote['upc'],
                    'down_count' => $vote['dnc']
                ];
            }

        }

        // Fill missing data
        foreach ($vidList as $vid) {

            if (!isset($result[$vid])) {
                $result[$vid] = self::$emptyVoteModel;
            }

        }

        return $result;
    }

    /**
     * 支持/反对
     *
     * @param $vote_id
     * @param $attitude
     *
     * @return array|bool
     */
    public static function vote($vote_id, $attitude)
    {

        $di    = \Phalcon\DI::getDefault();
        $mongo = $di->getShared('mongo');

        global $_UID;

        \VJ\User\ACL::check('PRIV_VOTE');

        $argv = [
            'vote_id'  => &$vote_id,
            'attitude' => &$attitude
        ];

        \VJ\Validator::filter($argv, [
            'vote_id'  => 'trim',
            'attitude' => 'int'
        ]);

        \VJ\Validator::validate($argv, [
            'vote_id'  => [
                'length' => [0, 50]
            ],
            'attitude' => [
                'in' => [self::ATTITUDE_UP, self::ATTITUDE_DOWN]
            ]
        ]);

        $record = $mongo->Vote->findOne(
            ['_id' => $vote_id],
            ['up.'.(string)$_UID => 1, 'dn.'.(string)$_UID => 1]
        );

        if ($record != null) {

            if (isset($record['up'][$_UID]) || isset($record['dn'][$_UID])) {
                // already voted
                throw new \VJ\Exception('ERR_VOTE_VOTED');
            }

            $updater = ['$set' => [], '$inc' => []];

            if ($attitude === self::ATTITUDE_UP) {
                $updater['$set']['up.'.$_UID] = time();
                $updater['$inc']['upc']       = 1;
            } else {
                $updater['$set']['dn.'.$_UID] = time();
                $updater['$inc']['dnc']       = 1;
            }

        } else {

            $updater = [
                '$set' => [
                    'up'  => new \stdClass(),
                    'dn'  => new \stdClass(),
                    'upc' => 0,
                    'dnc' => 0
                ]
            ];

            if ($attitude === self::ATTITUDE_UP) {
                $updater['$set']['up']->{$_UID} = time();
                $updater['$set']['upc']         = 1;
            } else {
                $updater['$set']['dn']->{$_UID} = time();
                $updater['$set']['dnc']         = 1;
            }

        }

        $result = $mongo->Vote->update(
            ['_id' => $vote_id],
            $updater,
            ['upsert' => true]
        );

        return ($result['n'] === 1);

    }

    /**
     * 删除整个评价数据，不单独被调用
     * [不检查权限]
     *
     * @param $vote_id
     *
     * @return bool
     */
    public static function _deleteEntity($vote_id)
    {

        $vote_id = (string)$vote_id;
        $mongo   = \Phalcon\DI::getDefault()->getShared('mongo');

        $result = $mongo->Vote->remove(
            ['_id' => $vote_id],
            ['justOne' => true]
        );

        return ($result['n'] === 1);

    }

}