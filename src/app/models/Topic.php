<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Topic
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Int */
    public $vote_id;

    /** @ODM\Int */
    public $dcz_id;

    /** @ODM\Int */
    public $_UID;

    /** @ODM\Date */
    public $time;

    /** @ODM\Date */
    public $mtime;

    /** @ODM\Date */
    public $stime;

    /** @ODM\String */
    public $title;

    /** @ODM\String */
    public $md;

    /** @ODM\String */
    public $text;

    /** @ODM\Int */
    public $replyc;

    /** @ODM\Int */
    public $viewc;

    /** @ODM\String */
    public $node;

    /** @ODM\String */
    public $node;

    /** @ODM\Boolean */
    public $hl;

}