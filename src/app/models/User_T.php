<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User_T
{

    /** @ODM\Id */
    public $id;

    /** @ODM\String */
    public $name;

    /** @ODM\String */
    public $type;

}