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

echo '<p>phalcon:';
echo_by_bool(extension_loaded('phalcon'));
echo '</p>';

echo '<p>redis:';
echo_by_bool(extension_loaded('redis'));
echo '</p>';

echo '<p>mongo:';
echo_by_bool(extension_loaded('mongo'));
echo '</p>';

echo '<p>gettext:';
echo_by_bool(extension_loaded('gettext'));
echo '</p>';

echo '<p>mbstring:';
echo_by_bool(extension_loaded('mbstring'));
echo '</p>';

echo '<p>curl:';
echo_by_bool(extension_loaded('curl'));
echo '</p>';

echo '<p>openssl:';
echo_by_bool(extension_loaded('openssl'));
echo '</p>';

