<?php


// Start runtimer
global $_START_TIME;
$_START_TIME = -microtime(true);


// Check extensions
if (!extension_loaded('phalcon')) {
    trigger_error('Extension \'phalcon\' is not loaded', E_USER_ERROR);
    exit();
}

if (!extension_loaded('apc')) {
    trigger_error('Extension \'apc\' is not loaded', E_USER_ERROR);
    exit();
}

if (!ini_get('apc.enabled')) {
    trigger_error('APC is not enabled', E_USER_WARNING);
}


// Start buffer
ob_start();


// Load configs
global $config;

require __dir__.'/../configs/project.php';
$config = new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/app.ini');
$config->merge(new Phalcon\Config\Adapter\Ini(APP_DIR.'configs/database.ini'));


// Constants
require APP_DIR.'includes/errorcode.php';
require APP_DIR.'includes/user.php';


// Composer
require APP_DIR.'vendor/autoload.php';


// I18N
setlocale(LC_ALL, 'zh_CN');
bindtextdomain('vijos', APP_DIR.'i18n');
textdomain('vijos');


// Headers
header('X-Frame-Options: SAMEORIGIN');
header('Content-Type: text/html;charset=utf-8');
header('X-XSS-Protection: 1;mode=block');


// Error Reporting
if (!$config->Debug->enabled) {
    error_reporting(0);
} else {
    error_reporting(E_ALL | E_STRICT);
}


// Check whether the requested hostname is in the allowed host list, which is
// defined in define/global.php. If not, generate a HTTP 403 error
if ($config->Security->checkHost && !in_array(ENV_HOST, (array)$config->Security->allowedHosts)) {
    header('HTTP/1.1 403 Forbidden', true, 403);
    exit('Bad Request: Header field "host" is invalid.');
}


// Set timezone
date_default_timezone_set($config->Localization->timezone);


// Using UTF-8 as default mbstring encoding
mb_internal_encoding('UTF-8');


// Template
global $_TEMPLATE_NAME;
$_TEMPLATE_NAME = $config->Template->default;