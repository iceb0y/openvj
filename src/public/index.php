<?php

require __DIR__.'/../app/includes/init.php';

//============================================

global $__CONFIG;

// Register services

$di = \Phalcon\DI::getDefault();

// MongoDB

$di->setShared('mongo', function () use ($__CONFIG)
{

    $mc = new \MongoClient($__CONFIG->Mongo->path, [

        'db'               => $__CONFIG->Mongo->database,
        'username'         => $__CONFIG->Mongo->username,
        'password'         => $__CONFIG->Mongo->password,
        'connectTimeoutMS' => $__CONFIG->Mongo->timeout

    ]);

    return $mc->selectDB($__CONFIG->Mongo->database);

});

$di->setShared('collectionManager', '\Phalcon\Mvc\Collection\Manager');

// Redis

$di->setShared('redis', function () use ($__CONFIG)
{

    $redis = new \Redis();
    $redis->connect($__CONFIG->Redis->path);

    return $redis;

});

// Cache

$di->set('cache', function() {

    $redis = \Phalcon\DI::getDefault()->getShared('redis');

    $frontend = new Phalcon\Cache\Frontend\Data([
        'lifetime' => 7200
    ]);

    $cache = new Phalcon\Cache\Backend\Redis($frontend, [
        'redis' => $redis
    ]);

    return $cache;
});

// View

$di->set('view', 'VJ\View\General');

\VJ\ErrorHandler\Error404::attach($di);

// Initialize session
\VJ\Session\Utils::initialize(new \VJ\Session\MongoProvider());

if ($__CONFIG->Security->enforceSSL) {
    \VJ\Security\SSL::enforce();
}

\VJ\Security\CSRF::initToken();
\VJ\Security\Session::initCharacter();

\VJ\User\Security\ACL::initialize();
\VJ\User\Account::initialize();

//============================================

$app = new \Phalcon\Mvc\Application($di);
echo $app->handle()->getContent();
