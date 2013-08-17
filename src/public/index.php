<?php

require __DIR__.'/../app/includes/init.php';

//============================================

global $__CONFIG;

// Register services

$di = \Phalcon\DI::getDefault();

$di->setShared('mongo', function () use ($__CONFIG) {

    $mc = new \MongoClient($__CONFIG->Mongo->path, [

        'db'               => $__CONFIG->Mongo->database,
        'username'         => $__CONFIG->Mongo->username,
        'password'         => $__CONFIG->Mongo->password,
        'connectTimeoutMS' => $__CONFIG->Mongo->timeout

    ]);

    return $mc->selectDB($__CONFIG->Mongo->database);

});

$di->setShared('collectionManager', '\Phalcon\Mvc\Collection\Manager');
$di->set('view', 'VJ\View\General');


\VJ\Phalcon::initSession();


if ($__CONFIG->Security->enforceSSL) {
    \VJ\Security\SSL::enforce();
}

\VJ\Security\CSRF::initToken();
\VJ\Security\Session::initCharacter();

\VJ\User\Security\Privilege::initialize();
\VJ\User\Account::initialize();

//============================================

$app = new \Phalcon\Mvc\Application($di);
echo $app->handle()->getContent();
