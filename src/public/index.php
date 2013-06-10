<?php

require __dir__.'/../app/includes/init.php';

//Bootstrap
//autoloader
$loader = new \Phalcon\Loader();
$loader
    ->registerDirs(array(APP_DIR.'controllers/'))
    ->registerNamespaces(array('VJ' => APP_DIR.'includes/'))
    ->register();

//dependency
$di = new Phalcon\DI\FactoryDefault();

$di->set('view', function() use ($_TEMPLATE_NAME) {

    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('../app/views/'.$_TEMPLATE_NAME.'/');
    $view->registerEngines(array('.volt' => function($view, $di) {

        global $config;

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(array(
            'compiledPath'      => ROOT_DIR.'runtime/compiled_templates/',
            'compiledExtension' => '.compiled',
            'compileAlways'     => (bool)$config->Template->compileAlways
        ));

        VJ\View::extendVolt($volt, $view);

        return $volt;

    }));

    VJ\View::extendView($view);

    return $view;
});
$di->set('session', function() {

    $session = new Phalcon\Session\Adapter\Redis(array(
        'path' => $config->Session->redisPath
    ));

    $session->start();
    return $session;
});

//redirect
if ($config->Compatibility->redirectOldURI) {
    VJ\Compatibility::redirectOldURI();
}

//app main
$app = new \Phalcon\Mvc\Application($di);
echo $app->handle()->getContent();