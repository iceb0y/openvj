<?php

function echo_by_bool($v, $text_ok = 'OK', $text_failed = 'Failed')
{
    if ($v)
        echo $text_ok;
    else
        echo $text_failed;

    echo PHP_EOL;
}

echo 'PHP:',PHP_EOL,PHP_EOL;

echo "\tPHP 5.4 or newer: ";
echo_by_bool(strnatcmp(phpversion(),'5.4.0') >= 0, phpversion(), phpversion());

echo PHP_EOL,'Extensions:',PHP_EOL,PHP_EOL;

echo "\tphalcon: ";
echo_by_bool(extension_loaded('phalcon'));

echo "\tredis: ";
echo_by_bool(extension_loaded('redis'));

echo "\tmongo: ";
echo_by_bool(extension_loaded('mongo'));

echo "\tgettext: ";
echo_by_bool(extension_loaded('gettext'));

echo "\tmbstring: ";
echo_by_bool(extension_loaded('mbstring'));

echo "\tcurl: ";
echo_by_bool(extension_loaded('curl'));

echo "\topenssl: ";
echo_by_bool(extension_loaded('openssl'));

echo "\tbcmath: ";
echo_by_bool(extension_loaded('bcmath'));

echo "\tgmp: ";
echo_by_bool(extension_loaded('gmp'));

