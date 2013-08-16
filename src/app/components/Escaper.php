<?php

namespace VJ;

class Escaper
{

    private static $escaper = null;
    private static $purifier = null;

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
            self::_initEscaper();
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
            self::_initEscaper();
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
            self::_initEscaper();
        }

        return self::$escaper->escapeUrl($content);

    }

    /**
     * Build HTTP query
     *
     * @param $query
     *
     * @return string
     */
    public static function uriQuery($query)
    {

        return http_build_query($query, '', '&');

    }

    /**
     * Init escaper object
     */
    private static function _initEscaper()
    {
        self::$escaper = new \Phalcon\Escaper();
    }

    /*
     * Init HTMLPurifier object
     */
    private static function _initPurifier()
    {
        if (defined('HTMLPURIFIER_ALLOW_CLASSES'))
            $all = '*[style|title|class]';
        else
            $all = '*[style|title]';

        $pconfig = \HTMLPurifier_Config::createDefault();
        $pconfig->set('Core.Encoding', 'UTF-8');
        $pconfig->set('AutoFormat.AutoParagraph', true);
        $pconfig->set('AutoFormat.RemoveEmpty', true);
        $pconfig->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $pconfig->set('HTML.Allowed', $all.',font[color|face],p,span,div,center,h3,h4,br,sub,sup,blockquote[cite],cite,q[cite],ol,ul,li,b,strong,strike,i,em,a[href],pre[lang]');
        $pconfig->set('CSS.AllowedProperties', 'font-family,font-style,font-weight,color,background-color,text-decoration,text-align,list-style-type');

        self::$purifier = new \HTMLPurifier($pconfig);
    }

    /**
     * Purify HTML
     *
     * @param $html
     *
     * @return mixed
     */
    public static function purify($html)
    {

        if (self::$purifier == null) {
            self::_initPurifier();
        }

        return self::$purifier->purify((string)$html);

    }

}