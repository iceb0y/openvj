<?php

namespace VJ\Database;

class Mongo
{

    /**
     * 连接Mongo
     */
    public static function connect()
    {

        global $__CONFIG, $mongo;

        $mc = new \MongoClient($__CONFIG->Mongo->path, array(

            'db'               => $__CONFIG->Mongo->database,
            'username'         => $__CONFIG->Mongo->username,
            'password'         => $__CONFIG->Mongo->password,
            'connectTimeoutMS' => $__CONFIG->Mongo->timeout

        ));

        $mongo = $mc->selectDB($__CONFIG->Mongo->database);

    }

}