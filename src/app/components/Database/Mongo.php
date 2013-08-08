<?php

namespace VJ\Database;

class Mongo {

    public static function connect()
    {

        global $config, $mongo;

        $mc = new \MongoClient($config->Mongo->path, array(

            'db' => $config->Mongo->database,
            'username' => $config->Mongo->username,
            'password' => $config->Mongo->password,
            'connectTimeoutMS' => $config->Mongo->timeout

        ));

        $mongo = $mc->selectDB($config->Mongo->database);

    }

}