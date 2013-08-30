<?php

require __DIR__.'/../../app/includes/init.php';

header('content-type:text/plain');

$aclScript = file_get_contents(__DIR__.'/acl.js');

$mongo = \Phalcon\DI::getDefault()->getShared('mongo');
var_dump($mongo->execute($aclScript));