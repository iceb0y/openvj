<?php


require __dir__.'/../app/includes/init.php';


//Bootstrap
//autoloader
$loader = new \Phalcon\Loader();
$loader
    ->registerDirs(array(APP_DIR.'controllers/'))
    ->registerNamespaces(array(
        'VJ'      => APP_DIR.'components/',
        'Phalcon' => APP_DIR.'vendor/phalcon/incubator/Library/Phalcon/'
    ))
    ->register();


//dependency
$di = new Phalcon\DI\FactoryDefault();

VJ\Phalcon::initWhoops($di);
VJ\Phalcon::initView($di);
VJ\Phalcon::initSession($di);
VJ\Security\CSRF::initToken($di);
//VJ\User::initGuest($di);


//redirect
if ($config->Compatibility->redirectOldURI) {
    VJ\Compatibility::redirectOldURI();
}


//app main
$app = new \Phalcon\Mvc\Application($di);
echo $app->handle()->getContent();