<?php

function check_failed($text = 'Failed')
{
    echo '<span class="failed">',$text,'</span>';
}

function check_ok($text = 'OK')
{
    echo '<span class="ok">',$text,'</span>';
}

function echo_by_bool($v, $text_ok = 'OK', $text_failed = 'Failed')
{
    if ($v)
        check_ok($text_ok);
    else
        check_failed($text_failed);
}

echo '<h2>PHP</h2>';

echo '<p>PHP 5.4 or newer:';
echo_by_bool(strnatcmp(phpversion(),'5.4.0') >= 0, phpversion(), phpversion());
echo '</p>';

echo '<h2>Extensions</h2>';

echo '<p>Phalcon:';
echo_by_bool(extension_loaded('phalcon'));
echo '</p>';

echo '<p>sundown:';
echo_by_bool(extension_loaded('sundown'));
echo '</p>';

echo '<p>APC:';
echo_by_bool(extension_loaded('apc'));
echo '</p>';

echo '<p>Mongo:';
echo_by_bool(extension_loaded('mongo'));
echo '</p>';

echo '<p>Redis:';
echo_by_bool(extension_loaded('redis'));
echo '</p>';

echo '<p>mbstring:';
echo_by_bool(extension_loaded('mbstring'));
echo '</p>';

echo '<p>CURL:';
echo_by_bool(extension_loaded('curl'));
echo '</p>';
