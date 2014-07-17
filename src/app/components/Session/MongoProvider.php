<?php

namespace VJ\Session;

class MongoProvider implements SessionProvider
{

    static $collection;

    public function __construct()
    {
        $di               = \Phalcon\DI::getDefault();
        self::$collection = $di->getShared('mongo')->ActiveSession;
    }

    public function newSession($sess_id, $data)
    {
        self::$collection->insert([
            '_id'  => $sess_id,
            'data' => serialize($data),
            'time' => new \MongoDate()
        ]);

        return true;
    }

    public function saveSession($sess_id, $data)
    {
        self::$collection->update([
            '_id' => $sess_id
        ], [
            '$set' => [
                'data' => serialize($data),
                'time' => new \MongoDate()
            ]
        ], [
            'upsert' => true
        ]);

        return true;
    }

    public function getSession($sess_id)
    {
        global $__CONFIG;

        $result = self::$collection->findOne([
            '_id' => $sess_id
        ]);

        if ($result == null) {
            return false;
        }

        // expired
        if ($result['time']->sec + $__CONFIG->Session->TTL < time()) {
            return false;
        }

        return unserialize($result['data']);
    }

    public function deleteSession($sess_id)
    {
        self::$collection->remove([
            '_id' => $sess_id
        ], [
            'justOne' => true
        ]);
    }
}