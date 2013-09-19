<?php

require __DIR__.'/../src/app/includes/init.php';

$result = \VJ\User\Account\Register::register('root', 'openvjroot', 'root', 1, 'accept', [

    'no_checking' => true,
    'ipmatch'     => '/^(127\.0\.0\.1|192\.168\.\d*\.\d*)$/'

]);

if ($result == true) {

    $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
    $mongo->User->update([
        'luser' => 'root'
    ], [
        '$set' => [
            'group' => GROUP_ROOT
        ]
    ]);

}

var_dump($result);