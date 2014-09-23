<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class ActiveSession
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String @ODM\UniqueIndex(order="asc") */
    private $sid;

    /** @ODM\String */
    private $data;

    /** @ODM\Date */
    private $time;

    function __construct($sid)
    {
        $this->sid = $sid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }
}