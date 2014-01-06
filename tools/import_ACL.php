<?php

require __DIR__.'/../src/app/includes/init.php';

$aclScript = json_decode(file_get_contents(__DIR__.'/acl.js'), true);

$mongo = \Phalcon\DI::getDefault()->getShared('mongo');
$mongo->System->update(
    ['_id' => \VJ\User\ACL::SYSTEM_ID_ACL],
    ['$set' => ['v' => $aclScript['acl']]],
    ['upsert' => true]
);
$mongo->System->update(
    ['_id' => \VJ\User\ACL::SYSTEM_ID_ACL_RULES],
    ['$set' => ['v' => $aclScript['acl_rules']]],
    ['upsert' => true]
);

$cache = \Phalcon\DI::getDefault()->getShared('cache');
$cache->save(\VJ\User\ACL::CACHE_ACL_KEY, $aclScript['acl']);