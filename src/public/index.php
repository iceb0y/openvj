<?php

require __DIR__.'/../app/includes/init.php';

//============================================

global $__CONFIG;

$di = \Phalcon\DI::getDefault();
$di->set('view', 'VJ\View\General');

\VJ\Database::initMongoDB();
\VJ\Database::initRedis();
\VJ\Cache::initialize();
\VJ\ErrorHandler\HTTPError::attach();
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
