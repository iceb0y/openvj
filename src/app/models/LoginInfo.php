<?php

namespace VJ\Models;

class LoginInfo extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {
        return 'RegValidation';
    }

    public $_id;

    public $time;

    public $uid;

    public $ok;

    public $form;

    public $ip;

    public $ua;

}