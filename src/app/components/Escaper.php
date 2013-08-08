<?php

namespace VJ;

class Escaper
{

    static $escaper;

    /**
     * Escape HTML
     *
     * @param $content
     *
     * @return string
     */
    public static function html($content)
    {

        if (self::$escaper == null) {
            self::$escaper = new \Phalcon\Escaper();
        }

        return self::$escaper->escapeHtml($content);

    }

    /**
     * Escape HTML attribute
     *
     * @param $content
     *
     * @return string
     */
    public static function htmlAttr($content)
    {

        if (self::$escaper == null) {
            self::$escaper = new \Phalcon\Escaper();
        }

        return self::$escaper->escapeHtmlAttr($content);

    }

    /**
     * Escape URI
     *
     * @param $content
     *
     * @return string
     */
    public static function uri($content)
    {

        if (self::$escaper == null) {
            self::$escaper = new \Phalcon\Escaper();
        }

        return self::$escaper->escapeUrl($content);

    }

}