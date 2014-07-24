<?php

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

if ( ! file_exists($file = __DIR__.'/../vendor/autoload.php')) {
        throw new RuntimeException('Install dependencies to run this script.');
}

$loader = require_once $file;
// $loader->add('Documents', __DIR__);

$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('Vijos');
$config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/../models'));

AnnotationDriver::registerAnnotationClasses();

// global $dm;
$dm = DocumentManager::create($connection, $config);
