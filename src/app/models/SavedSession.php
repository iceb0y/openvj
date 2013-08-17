<?php

namespace VJ\Models;

class SavedSession extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {
        return 'SavedSession';
    }

    public $_id;

    public $uid;

    public $key;

    public $exptime;

}