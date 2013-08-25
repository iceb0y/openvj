<?php

namespace VJ;

class Cache
{

    public static function initialize()
    {

        $di = \Phalcon\DI::getDefault();

        $di->setShared('cache', function () {

            $redis = \Phalcon\DI::getDefault()->getShared('redis');

            $frontend = new \Phalcon\Cache\Frontend\Data([
                'lifetime' => 7200
            ]);

            $cache = new \Phalcon\Cache\Backend\Redis($frontend, [
                'redis' => $redis
            ]);

            return $cache;
        });

    }

}