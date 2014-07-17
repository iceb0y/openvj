<?php

namespace VJ\IO;

class Bgservice
{

    public static function get($URI, $get = null)
    {
        global $__CONFIG;

        return \VJ\IO\Utils::curl(
            $__CONFIG->IO_Bgservice->host,
            $__CONFIG->IO_Bgservice->port,
            $__CONFIG->IO_Bgservice->timeout,
            $URI,
            $get
        );
    }

    public static function post($URI, $post = null)
    {
        global $__CONFIG;

        return \VJ\IO\Utils::curl(
            $__CONFIG->IO_Bgservice->host,
            $__CONFIG->IO_Bgservice->port,
            $__CONFIG->IO_Bgservice->timeout,
            $URI,
            null,
            $post
        );
    }
}
