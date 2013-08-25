<?php

require __DIR__.'/../app/includes/init.php';

//============================================

global $__CONFIG;

\VJ\View\General::initialize();
\VJ\ErrorHandler\HTTPError::attach();

if ($__CONFIG->Security->enforceSSL) {
    \VJ\Security\SSL::enforce();
}

\VJ\Security\CSRF::initToken();
\VJ\Security\Session::initCharacter();

\VJ\User\Account::initialize();

//============================================

$app = new \Phalcon\Mvc\Application(\Phalcon\DI::getDefault());
echo $app->handle()->getContent();
