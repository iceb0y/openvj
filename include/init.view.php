<?php

//
// All PHP files which generate views should include this file
//

// Charset & XSS Protection
header('Content-Type: text/html;charset=utf-8');
header('X-XSS-Protection: 1;mode=block');

require_once __DIR__.'/init.php';
require_once INCLUDE_DIR.'class.view.php';

// TODO: Determine which view to use by session
global $_TEMPLATE_NAME, $_CONFIG, $_TEMPLATE_DIR;
$_TEMPLATE_NAME = $_CONFIG['TEMPLATE'];
$_TEMPLATE_DIR  = VIEW_DIR.$_TEMPLATE_NAME.'/';

View::setRoot($_TEMPLATE_DIR);
View::setVariable(array
(
    'TEMPLATE_DIR' => 'view/'.$_TEMPLATE_NAME
));
