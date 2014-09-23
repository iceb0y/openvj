<?php

namespace VJ\Session;

class MongoProvider implements SessionProvider
{

    static $collection;
    static $dm;

    public function __construct()
    {
        $di       = \Phalcon\DI::getDefault();
        self::$dm = $di->getShared('dm');
    }

    public function newSession($sess_id, $data)
    {
        $session = new \VJ\Models\ActiveSession($sess_id);
        $session->setData(serialize($data));

        self::$dm->flush();

        return true;
    }

    public function saveSession($sess_id, $data)
    {
        self::$dm
            ->createQueryBuilder('VJ\Models\ActiveSession')
            ->update()
            ->upsert(true)
            ->field('sid')->equals($sess_id)
            ->field('data')->set(serialize($data))
            ->field('time')->set(new \MongoDate())
            ->getQuery()
            ->execute();

        return true;
    }

    public function getSession($sess_id)
    {
        global $__CONFIG;

        $session = self::$dm
            ->createQueryBuilder('VJ\Models\ActiveSession')
            ->field('sid')->equals($sess_id)
            ->getQuery()
            ->getSingleResult();

        if ($session == null) {
            return false;
        }

        // expired
        if ($session->getTime()->getTimestamp() + $__CONFIG->Session->TTL < time()) {
            return false;
        }

        return unserialize($session->getData());
    }

    public function deleteSession($sess_id)
    {
        self::$dm
            ->createQueryBuilder('VJ\Models\ActiveSession')
            ->remove()
            ->field('sid')->equals($sess_id)
            ->getQuery()
            ->execute();

        return true;
    }
}