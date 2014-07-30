<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class System
{
    /** @ODM\Id(strategy="NONE") */
    public $id;

    /** @ODM\String */
    public $v;

    /** @ODM\String */
    public $d;

}