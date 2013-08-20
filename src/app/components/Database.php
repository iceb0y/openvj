<?php

namespace VJ;

class Database
{

    const COUNTER_USER_ID    = 0;
    const COUNTER_PROBLEM_ID = 1;

    public static function increaseId($id)
    {

        $id    = (int)$id;
        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $seq = $mongo->command([
            'findandmodify' => 'Counter',
            'query'         => ['_id' => $id],
            'update'        => ['$inc' => ['c' => 1]],
            'new'           => true,
            'upsert'        => true
        ]);

        if ($seq['value']['c'] == null) {
            return 0;
        } else {
            return $seq['value']['c'];
        }

    }

}