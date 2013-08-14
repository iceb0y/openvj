<?php

namespace VJ\Database;

class Redis
{

    /**
     * 连接Redis
     * @throws \RedisException
     */
    public static function connect()
    {

        global $__CONFIG, $redis;

        $redis  = new \redis();
        $result = $redis->pconnect($__CONFIG->Session->redisPath);

        if ($result !== true) {
            throw new \RedisException('Cannot connect to Redis server');
        }

    }

}