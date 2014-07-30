<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Vote
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Int */
    public $upc;

    /** @ODM\Int */
    public $dnc;

    /** @ODM\Hash */
    public $up;

    /** @ODM\Hash */
    public $dn;

}