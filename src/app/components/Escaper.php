<?php

namespace VJ;

class Escaper
{

    static $escaper;

    public static function html($content)
    {

        if (self::$escaper == null)
            self::$escaper = new \Phalcon\Escaper();

        return self::$escaper->escapeHtml($content);

    }

    public static function htmlAttr($content)
    {

        if (self::$escaper == null)
            self::$escaper = new \Phalcon\Escaper();

        return self::$escaper->escapeHtmlAttr($content);

    }

    public static function uri($content)
    {

        if (self::$escaper == null)
            self::$escaper = new \Phalcon\Escaper();

        return self::$escaper->escapeUrl($content);

    }

}