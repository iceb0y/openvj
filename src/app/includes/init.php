<?php


// Start buffer
ob_start();


// Load configs
global $__CONFIG;

require __dir__.'/../configs/project.php';
$__CONFIG = new Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/app.ini'));
$__CONFIG->merge(new Phalcon\Config((array)new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/database.ini')));


// Constants
require APP_DIR.'includes/user.php';
require APP_DIR.'includes/privilege.php';

// Autoloader
require_once APP_DIR.'vendor/autoload.php';

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

    if ($__CONFIG->Compatibility->redirectOldURI) {
        \VJ\Compatibility::redirectOldURI();
    }

    // Headers
    header('X-Frame-Options: SAMEORIGIN');
    header('Content-Type: text/html;charset=utf-8');
    header('X-XSS-Protection: 1;mode=block');


    //===========================================================================
    // Check whether the requested hostname is in the allowed host list, which is
    // defined in config file. If not, generate a HTTP 403 error
    if ($__CONFIG->Security->checkHost && !in_array(ENV_HOST, (array)$__CONFIG->Security->allowedHosts)) {
        header('HTTP/1.1 403 Forbidden', true, 403);
        exit('Bad Request: Header field "host" is invalid.');
    }
    //===========================================================================

    // Template
    global $__TEMPLATE_NAME;
    $__TEMPLATE_NAME = $__CONFIG->Template->default;

}


// Dependency Injection
new \Phalcon\DI\FactoryDefault();


// Error Reporting
\VJ\ErrorHandler::whoops();
\VJ\ErrorHandler::phalcon();


// Using UTF-8 as default mbstring encoding
mb_internal_encoding('UTF-8');


// I18N
date_default_timezone_set($__CONFIG->Localization->timezone);
global $__LANG, $__LANG_DEFAULT;
$__LANG_DEFAULT = $__CONFIG->Localization->defaultLanguage;
$__LANG = $__LANG_DEFAULT;
\VJ\I18N::detectLanguage();
\VJ\I18N::loadLanguage();


// Set timezone and datetime locale
global $__DATE_FORMAT, $__TIME_FORMAT;

$__DATE_FORMAT = \VJ\I18N::get('DATE_FORMAT');
$__TIME_FORMAT = \VJ\I18N::get('TIME_FORMAT');


// I18N (deprecated)
setlocale(LC_ALL, 'zh_CN.UTF-8');
bindtextdomain('vijos', APP_DIR.'i18n');
textdomain('vijos');


// Initialize components
\VJ\Database::initMongoDB();
\VJ\Database::initRedis();
\VJ\Cache::initialize();
\VJ\Session\Utils::initialize(new \VJ\Session\MongoProvider());
\VJ\User\ACL::initialize();