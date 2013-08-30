<?php

set_include_path(__DIR__.'/../src/app/vendor/phpseclib/phpseclib/phpseclib/');
require_once('Crypt/RSA.php');

define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
$rsa = new Crypt_RSA();
$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_RAW);
$key = $rsa->createKey(1024);
echo $key['privatekey'];
echo "\n\n";
$e = new Math_BigInteger($key['publickey']['e'], 10);
$n = new Math_BigInteger($key['publickey']['n'], 10);
echo "Public Key:\n";
echo $e->toHex();
echo "\n";
echo $n->toHex();