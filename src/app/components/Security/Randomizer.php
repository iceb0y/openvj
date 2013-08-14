<?php

namespace VJ\Security;

class Randomizer
{

    public static function toHex($bytes = 10)
    {
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }

    public static function toSHA1($bytes = 10)
    {
        return sha1(self::toHex($bytes));
    }

}