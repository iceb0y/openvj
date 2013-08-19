<?php

namespace VJ\Models;

class LoginInfo extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {
        return 'LoginInfo';
    }

    public $_id;

    public $time;

    public $uid;

    public $ok;

    public $from;

    public $ip;

    public $ua;

}