<?php

require __DIR__.'/../src/app/includes/init.php';

$result = \VJ\User\Account\Register::register('root', 'openvjroot', 'root', 1, 'accept', [

    'no_checking' => true,
    'ipmatch'     => '/^10\.22\.22\.1$/'

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