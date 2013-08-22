<?php

namespace VJ\Formatter;

class Markdown
{

    private static $md = null;

    /**
     * parse markdown to html
     *
     * @param $content
     *
     * @return mixed
     */
    public static function parse($content)
    {

        if (self::$md == null) {
            self::$md = new \Michelf\MarkdownExtra();
        }

        return \VJ\Escaper::purify(self::$md->transform($content));

    }

}