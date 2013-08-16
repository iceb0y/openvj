<?php

namespace VJ\Models;

class User extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {
        return 'User';
    }

}