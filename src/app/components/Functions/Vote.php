<?php

namespace VJ\Functions;

use \VJ\I;

class Vote
{

    const ATTITUDE_UP = 0;
    const ATTITUDE_DOWN = 1;

    /**
     * 获取投票内容
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

        $result = [
            'up_count'      => 0,
            'down_count'    => 0
        ];

        if ($record != null) {
            $result['up_count'] = $record['upc'];
            $result['down_count'] = $record['dnc'];
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

        global $_UID;

        $di    = \Phalcon\DI::getDefault();
        $acl   = $di->getShared('acl');
        $mongo = $di->getShared('mongo');

        $vote_id = (string)$vote_id;
        $attitude = (int)$attitude;

        if (strlen($vote_id) > 50) {
            return I::error('ARGUMENT_TOO_LONG', 'vote_id', 50);
        }

        if ($attitude !== self::ATTITUDE_UP && $attitude !== self::ATTITUDE_DOWN) {
            return I::error('ARGUMENT_INVALID', 'attitude');
        }

        if (!$acl->has(PRIV_VOTE)) {
            return I::error('NO_PRIV', 'PRIV_VOTE');
        }

        $record = $mongo->Vote->findOne(
            ['_id' => $vote_id],
            ['up.'.(string)$_UID => 1, 'dn.'.(string)$_UID => 1]
        );

        if ($record != null) {

            if (isset($record['up'][$_UID]) || isset($record['dn'][$_UID])) {
                // already voted
                return I::error('VOTE_VOTED');
            }

            $updater = ['$set' => [], '$inc' => []];

            if ($attitude === self::ATTITUDE_UP) {
                $updater['$set']['up.'.$_UID] = time();
                $updater['$inc']['upc'] = 1;
            } else {
                $updater['$set']['dn.'.$_UID] = time();
                $updater['$inc']['dnc'] = 1;
            }

        } else {

            $updater = [
                '$set' => [
                    'up' => new \stdClass(),
                    'dn' => new \stdClass(),
                    'upc' => 0,
                    'dnc' => 0
                ]
            ];

            if ($attitude === self::ATTITUDE_UP) {
                $updater['$set']['up']->{$_UID} = time();
                $updater['$set']['upc'] = 1;
            } else {
                $updater['$set']['dn']->{$_UID} = time();
                $updater['$set']['dnc'] = 1;
            }

        }

        $result = $mongo->Vote->update(
            ['_id' => $vote_id],
            $updater,
            ['upsert' => true]
        );

        return ($result['n'] === 1);

    }

}