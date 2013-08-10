<?php

define('ENV_REQUEST_TIME', isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true));
define('ENV_SSL', (bool)(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'));
define('ENV_HOST', $_SERVER['HTTP_HOST']);
define('ENV_HOST_URL', (ENV_SSL ? 'https' : 'http').'://'.ENV_HOST);
define('ENV_NODE_HOST_IO', ENV_HOST_URL.'/node');
define('ENV_NODE_PORT_IO', '1234');
define('ROOT_DIR', __dir__.'/../../');
define('APP_DIR', ROOT_DIR.'app/');

define('APP_NAME', 'OpenVJ');
define('APP_VERSION', 'α');
