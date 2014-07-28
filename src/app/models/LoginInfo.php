<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class LoginInfo
{

    /** @ODM\Id */
    public $_id;

    /** @ODM\Date */
    public $time;

    /** @ODM\String */
    public $uid;

    /** @ODM\Boolean */
    public $ok;

    /** @ODM\String */
    public $from;

    /** @ODM\String */
    public $ip;

    /** @ODM\String */
    public $ua;

}