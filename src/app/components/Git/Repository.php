<?php

namespace VJ;

use \VJ\IO\Gitservice;

class Repository
{

    public static function create($name)
    {

        global $__CONFIG;

        $name = (string)$name;

        $result = Gitservice::post('/repository/create', [
            'path' => $__CONFIG->Data->gitPath.'/'.$name
        ]);

        return $result;

    }

}