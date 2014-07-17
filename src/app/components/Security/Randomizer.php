<?php

namespace VJ\Security;

class Randomizer
{

    public static function toBinary($bytes = 10)
    {
        return openssl_random_pseudo_bytes($bytes);
    }

    public static function toHex($bytes = 10)
    {
        return bin2hex(self::toBinary($bytes));
    }

    public static function toBase64($bytes = 10)
    {
        return base64_encode(self::toBinary($bytes));
    }

    public static function toSHA1($bytes = 10)
    {
        return sha1(self::toBinary($bytes));
    }
}