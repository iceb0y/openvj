<?php

namespace VJ\Database;

class Redis {

    public static function connect()
    {

        global $config, $redis;

        $redis = new \redis();
        $result = $redis->pconnect($config->Session->redisPath);

        if ($result !== true)
        {
            throw new \RedisException('Cannot connect to Redis server');
        }

    }

}