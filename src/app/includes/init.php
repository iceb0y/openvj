<?php


// Start buffer
ob_start();


// Load configs
global $__CONFIG;

require __dir__.'/../configs/project.php';
$__CONFIG = new Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/app.ini'));
$__CONFIG->merge(new Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/database.ini')));
$__CONFIG->merge(new Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/security.ini')));


// Constants
require APP_DIR.'includes/errorcode.php';
require APP_DIR.'includes/user.php';
require APP_DIR.'includes/privilege.php';


// Autoloader
require APP_DIR.'vendor/autoload.php';

(new \Phalcon\Loader)
    ->registerDirs([
        APP_DIR.'controllers/'
    ])
    ->registerNamespaces([
        'VJ'        => APP_DIR.'components/',
        'VJ\Models' => APP_DIR.'models/',
        'Phalcon'   => APP_DIR.'vendor/phalcon/incubator/Library/Phalcon/'
    ])
    ->register();


if (PHP_SAPI !== 'cli') {

    // Headers
    header('X-Frame-Options: SAMEORIGIN');
    header('Content-Type: text/html;charset=utf-8');
    header('X-XSS-Protection: 1;mode=block');


    //===========================================================================
    // Check whether the requested hostname is in the allowed host list, which is
    // defined in define/global.php. If not, generate a HTTP 403 error
    if ($__CONFIG->Security->checkHost && !in_array(ENV_HOST, (array)$__CONFIG->Security->allowedHosts)) {
        header('HTTP/1.1 403 Forbidden', true, 403);
        exit('Bad Request: Header field "host" is invalid.');
    }
    //===========================================================================


    if ($__CONFIG->Compatibility->redirectOldURI) {
        \VJ\Compatibility::redirectOldURI();
    }


    // Template
    global $__TEMPLATE_NAME;
    $__TEMPLATE_NAME = $__CONFIG->Template->default;

}


// Dependency Injection
new \Phalcon\DI\FactoryDefault();


// Error Reporting
if (!$__CONFIG->Debug->enabled) {
    error_reporting(0);
} else {
    error_reporting(E_ALL | E_STRICT);
    new \VJ\ErrorHandler\WhoopsServiceProvider();
}


// Set timezone and datetime locale
global $__DATE_FORMAT, $__TIME_FORMAT;

date_default_timezone_set($__CONFIG->Localization->timezone);

$__DATE_FORMAT = $__CONFIG->Localization->defaultDateFormat;
$__TIME_FORMAT = $__CONFIG->Localization->defaultTimeFormat;


// Using UTF-8 as default mbstring encoding
mb_internal_encoding('UTF-8');


// I18N
setlocale(LC_ALL, 'zh_CN.UTF-8');
bindtextdomain('vijos', APP_DIR.'i18n');
textdomain('vijos');


// Initialize components
\VJ\Database::initMongoDB();
\VJ\Database::initRedis();
\VJ\Cache::initialize();
\VJ\Session\Utils::initialize(new \VJ\Session\MongoProvider());
\VJ\User\ACL::initialize();