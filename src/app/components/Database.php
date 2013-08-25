<?php

namespace VJ;

class Database
{

    const COUNTER_USER_ID    = 0;
    const COUNTER_PROBLEM_ID = 1;

    public static function initMongoDB()
    {

        global $__CONFIG;

        $di = \Phalcon\DI::getDefault();

        $di->setShared('mongo', function () use ($__CONFIG) {

            $mc = new \MongoClient($__CONFIG->Mongo->path, [

                'db'               => $__CONFIG->Mongo->database,
                'username'         => $__CONFIG->Mongo->username,
                'password'         => $__CONFIG->Mongo->password,
                'connectTimeoutMS' => $__CONFIG->Mongo->timeout

            ]);

            return $mc->selectDB($__CONFIG->Mongo->database);

        });

        $di->set('collectionManager', '\Phalcon\Mvc\Collection\Manager');

    }

    public static function initRedis()
    {

        global $__CONFIG;

        $di = \Phalcon\DI::getDefault();

        $di->setShared('redis', function () use ($__CONFIG) {

            $redis = new \Redis();
            $redis->connect($__CONFIG->Redis->path);

            return $redis;

        });

    }

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