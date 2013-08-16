<?php

namespace VJ\Models;

class RegValidation extends \Phalcon\Mvc\Collection
{

    public $_id;

    public $email;

    public $code;

    public $time;

    public function getSource()
    {
        return 'RegValidation';
    }

}