<?php

namespace VJ;

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;


class Database
{

    const COUNTER_USER_ID    = 0;
    const COUNTER_PROBLEM_ID = 1;

    public static function initMongoDB()
    {
        global $__CONFIG;

        $mc=new \MongoClient($__CONFIG->Mongo->path, [
            'db'               => $__CONFIG->Mongo->database,
            'username'         => $__CONFIG->Mongo->username,
            'password'         => $__CONFIG->Mongo->password,
            'connectTimeoutMS' => $__CONFIG->Mongo->timeout
        ]);

        $connection = new Connection($mc);


        $config = new Configuration();
        $config->setProxyDir(ROOT_DIR.'runtime/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(ROOT_DIR.'runtime/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB($__CONFIG->Mongo->database);
        $config->setMetadataDriverImpl(AnnotationDriver::create(APP_DIR.'models'));

        AnnotationDriver::registerAnnotationClasses();

        global $dm;
        $dm=DocumentManager::create($connection,$config);

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

        global $dm;

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