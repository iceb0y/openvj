<?php

namespace VJ\Formatter;

class Markdown
{
    private static $init = false;

    public static function parse($md)
    {
        if (self::$init == false) {
            \Marked\Marked::setOptions([
                'gfm'        => true,
                'tables'     => false,
                'breaks'     => true,
                'langPrefix' => 'prettyprint lang-'
            ]);

            self::$init = true;
        }

        return \VJ\Escaper::purify(\Marked\Marked::render($md));
    }
}