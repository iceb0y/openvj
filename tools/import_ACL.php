<?php

require __DIR__.'/../src/app/includes/init.php';

$aclScript = file_get_contents(__DIR__.'/acl.js');

$mongo = \Phalcon\DI::getDefault()->getShared('mongo');
var_dump($mongo->execute($aclScript));