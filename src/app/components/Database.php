<?php

namespace VJ;

use \Phalcon\DI;
use Doctrine\MongoDB as MongoDB;
use Doctrine\ODM\MongoDB as ODM;

class Database
{

    const COUNTER_USER_ID    = 0;
    const COUNTER_PROBLEM_ID = 1;

    public static function initMongoDB()
    {
        global $__CONFIG;

        $di = \Phalcon\DI::getDefault();
        $di->setShared('mongoClient', function () use ($__CONFIG) {

            return new \MongoClient($__CONFIG->Mongo->path, [

                'db'               => $__CONFIG->Mongo->database,
                'username'         => $__CONFIG->Mongo->username,
                'password'         => $__CONFIG->Mongo->password,
                'connectTimeoutMS' => $__CONFIG->Mongo->timeout

            ]);
        });

        $di->setShared('mongo', function () use ($__CONFIG, $di) {
            $mc = $di['mongoClient'];

            return $mc->selectDB($__CONFIG->Mongo->database);
        });

        $di->setShared('collectionManager', function () use ($__CONFIG, $di) {
            $config = new ODM\Configuration();
            $config->setProxyDir(__DIR__ . '/Proxies');
            $config->setProxyNamespace('Proxies');
            $config->setHydratorDir(APP_DIR.'runtime/Hydrators/');
            $config->setHydratorNamespace('Hydrators');
            $config->setDefaultDB($__CONFIG->Mongo->database);
            $config->setMetadataDriverImpl(ODM\Mapping\Driver\AnnotationDriver::create(APP_DIR.'models/'));

            ODM\Mapping\Driver\AnnotationDriver::registerAnnotationClasses();

            $connection = new MongoDB\Connection($di->getShared('mongoClient'));

            return ODM\DocumentManager::create($connection, $config);
        });
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
        $id=(int)$id;

        $di = \Phalcon\DI::getDefault();
        $dm = $di->getShared('collectionManager');

        $dat=$dm->createQueryBuilder('VJ\Models\DataBase')
                ->findAndUpdate()
                ->returnNew()
                ->upsert(true)
                ->field('id')->equals($id)
                ->field('COUNTER')->inc(1)
                ->getQuery()
                ->execute();

        return $dat->COUNTER;  
    }
}