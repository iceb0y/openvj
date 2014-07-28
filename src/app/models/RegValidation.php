<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class RegValidation
{

    /** @ODM\Id */
    public $_id;

    /** @ODM\String */
    public $email;

    /** @ODM\String */
    public $code;

    /** @ODM\Date */
    public $time;

}