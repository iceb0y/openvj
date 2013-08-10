<?php

require __DIR__.'/../app/includes/init.php';

//============================================

\VJ\Phalcon::initView();
\VJ\Phalcon::initSession();

\VJ\Security\SSL::force();
\VJ\Security\CSRF::initToken();
\VJ\Security\Session::initCharacter();

\VJ\User\Security\Privilege::initialize();
\VJ\User\Account::initialize();

\VJ\Node::io($_SERVER['HTTP_HOST']);

//============================================

$app = new \Phalcon\Mvc\Application(\Phalcon\DI::getDefault());
echo $app->handle()->getContent();
