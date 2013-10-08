<?php

define('OPENVJ_ACL_DISABLE', true);

require __DIR__.'/../src/app/includes/init.php';

$uid = null;
$existance = \VJ\User\Account::usernameExists('root', $uid);

if (!in_array('--force', $argv)) {

    if ($existance) {
        echo "User \"root\" is already existed (uid=$uid), operation aborted.\nUse --force to override.\n";
        exit();
    }

} else {

    if ($existance) {
        \VJ\User\Account::delete($uid, true);
        echo "Warning: User \"root\" has been overridden (uid=$uid).\n";
    }

}

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