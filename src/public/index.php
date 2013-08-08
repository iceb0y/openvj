<?php

require __dir__.'/../app/includes/init.php';

//============================================

\VJ\Phalcon::initView();
\VJ\Phalcon::initSession();

\VJ\Security\SSL::force();
\VJ\Security\CSRF::initToken();

\VJ\User\Security\Privilege::initialize();
\VJ\User\Account::initialize();

//============================================

$app = new \Phalcon\Mvc\Application(\Phalcon\DI::getDefault());
echo $app->handle()->getContent();