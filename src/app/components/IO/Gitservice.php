<?php

namespace VJ\IO;

class Gitservice
{

    public static function get($URI, $get = null)
    {

        global $__CONFIG;

        return \VJ\IO\Utils::curl(
            $__CONFIG->IO_Gitservice->host,
            $__CONFIG->IO_Gitservice->port,
            $__CONFIG->IO_Gitservice->timeout,
            $URI,
            $get
        );

    }

    public static function post($URI, $post = null)
    {

        global $__CONFIG;

        return \VJ\IO\Utils::curl(
            $__CONFIG->IO_Gitservice->host,
            $__CONFIG->IO_Gitservice->port,
            $__CONFIG->IO_Gitservice->timeout,
            $URI,
            null,
            $post
        );

    }

}