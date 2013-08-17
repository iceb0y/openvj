<?php

namespace VJ\Models;

class RegValidation extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {
        return 'RegValidation';
    }

    public $_id;

    public $email;

    public $code;

    public $time;

}