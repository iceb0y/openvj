<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class DataBase
{

    /** @ODM\Id(strategy="NONE") */
    public $id;

    /** @ODM\Int */
    public $COUNTER;

}