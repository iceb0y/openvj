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

        global $config, $redis;

        $redis  = new \redis();
        $result = $redis->pconnect($config->Session->redisPath);

        if ($result !== true) {
            throw new \RedisException('Cannot connect to Redis server');
        }

    }

}