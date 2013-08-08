<?php


// Start runtimer
global $_START_TIME;
$_START_TIME = -microtime(true);


// Start buffer
ob_start();


// Load configs
global $config;

require __dir__.'/../configs/project.php';
$config = new \Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/app.ini'));
$config->merge(new \Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/database.ini')));


// Constants
require APP_DIR.'includes/errorcode.php';
require APP_DIR.'includes/user.php';
require APP_DIR.'includes/privilege.php';


// Autoloader
require APP_DIR.'vendor/autoload.php';

(new \Phalcon\Loader)
    ->registerDirs(array(APP_DIR.'controllers/'))
    ->registerNamespaces(array(
        'VJ'      => APP_DIR.'components/',
        'Phalcon' => APP_DIR.'vendor/phalcon/incubator/Library/Phalcon/'
    ))
    ->register();
unset($loader);


// Headers
header('X-Frame-Options: SAMEORIGIN');
header('Content-Type: text/html;charset=utf-8');
header('X-XSS-Protection: 1;mode=block');


//===========================================================================
// Check whether the requested hostname is in the allowed host list, which is
// defined in define/global.php. If not, generate a HTTP 403 error
if ($config->Security->checkHost && !in_array(ENV_HOST, (array)$config->Security->allowedHosts)) {
    header('HTTP/1.1 403 Forbidden', true, 403);
    exit('Bad Request: Header field "host" is invalid.');
}

if ($config->Compatibility->redirectOldURI) {
    \VJ\Compatibility::redirectOldURI();
}
//===========================================================================


// Dependency Injection
new \Phalcon\DI\FactoryDefault();


// Error Reporting
if (!$config->Debug->enabled) {
    error_reporting(0);
} else {
    error_reporting(E_ALL | E_STRICT);
    \VJ\Phalcon::initWhoops();
}


// Set timezone
date_default_timezone_set($config->Localization->timezone);


// Using UTF-8 as default mbstring encoding
mb_internal_encoding('UTF-8');


// I18N
setlocale(LC_ALL, 'zh_CN');
bindtextdomain('vijos', APP_DIR.'i18n');
textdomain('vijos');


// Connect to database
\VJ\Database\Mongo::connect();
\VJ\Database\Redis::connect();


// Template
global $_TEMPLATE_NAME;
$_TEMPLATE_NAME = $config->Template->default;