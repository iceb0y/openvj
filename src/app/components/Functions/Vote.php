<?php

namespace VJ\Functions;

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
        global $dm;

        $record=$dm->getRepositort('VJ\Models\Vote')->findOneBy(['id' => $vote_id]);

        $result = self::$emptyVoteModel;

        if ($record != null) {
            $result['up_count']   = $record->upc;
            $result['down_count'] = $record->dnc;
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

        global $dm;

        $result = [];

        // Separate into many chunks.
        foreach ($vidLists as $list) {

            $cursor=$dm->getRepository('VJ/Models/Vote')->findBy(['id'=>['in' => $list]])

            foreach ($cursor as $vote) {
                $result[$vote->id] = [
                    'up_count'   => $vote->upc,
                    'down_count' => $vote->dnc
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
        global $dm;

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

        $record=$dm->getRepository('VJ\Models\Vote')->findOneBy(['id' => $vote_id]);

        if ($record != null) {

            if (isset($record['up'][$_UID]) || isset($record['dn'][$_UID])) {
                // already voted
                throw new \VJ\Exception('ERR_VOTE_VOTED');
            }
        } else {

            $dm->createQueryBuilder('VJ\Models\Vote')
                   ->update()
                   ->upsert(true)
                   ->field('id')->equals($vote_id)
                   ->setNewObj([
                    'up'    =>  new \stdClass(),
                    'dn'    =>  new \stdClass(),
                    'upc'  =>  0,
                    'dnc'  =>  0])
                   ->getQuery()
                   ->execute();

            }

        }

        $set='';
        $inc='';
        if ($attitude === self::ATTITUDE_UP) {
            // $updater['$set']['up']->{$_UID} = time();
            $set='up'.'.'.(String)$_UID;
            $inc='upc';
        } else {
            $updater['$set']['dn']->{$_UID} = time();
            $set='dn'.'.'.(String)$_UID;
            $inc='dnc';
        }

        $dm->createQueryBuilder('VJ\Models\Vote')
               ->update()
               ->field($set)->set(time())
               ->field($inc)->inc(1)
               ->getQuery()
               ->execute();

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
        global $dm;

        $result=$dm->createQueryBuilder('VJ\Models\Vote')
                             // ->findAndRemove()
                             ->remove();
                             ->field('id')->equals($vote_id)
                             ->getQuery()
                             ->execute();

        // $result['n']

        return ($result['n'] === 1);
    }
}