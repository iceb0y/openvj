<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Discussion
{
    /** @ODM\Id(strategy="NONE") */
    public $id;

    /** @ODM\Collection */
    public $r;

    /** @ODM\String */
    public $luser;

    /** @ODM\Date */
    public $ltime;

    /** @ODM\Int */
    public $count;

    /** @ODM\Int */
    public $countc;

}