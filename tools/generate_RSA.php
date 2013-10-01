<?php

set_include_path(__DIR__.'/../src/app/vendor/phpseclib/phpseclib/phpseclib/');
require_once('Crypt/RSA.php');

define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
$rsa = new Crypt_RSA();
$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_RAW);

// Private key

$key = $rsa->createKey(1024);
echo 'Private key:',PHP_EOL,PHP_EOL;
echo $key['privatekey'];

// Public key

$e = new Math_BigInteger($key['publickey']['e'], 10);
$n = new Math_BigInteger($key['publickey']['n'], 10);
echo PHP_EOL,PHP_EOL,'Public key:',PHP_EOL,PHP_EOL;
echo 'e = ', $e->toHex();
echo PHP_EOL;
echo 'n = ', $n->toHex();
echo PHP_EOL;