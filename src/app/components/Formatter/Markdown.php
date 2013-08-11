<?php

namespace VJ\Formatter;

class Markdown
{

    private static $md = null;

    /**
     * fix: <pre><code>中<>被两次escape
     *
     * @param $html
     *
     * @return string
     */
    private static function _fixCodeBlock($html)
    {
        $pBegin = 0;

        while (false !== $pBegin = stripos($html, '<code>', $pBegin)) {
            $pEnd = strpos($html, '</code>', $pBegin + 6);
            if ($pEnd === false) break;

            $inner = substr($html, $pBegin + 6, $pEnd - $pBegin - 6);
            $inner = str_replace('&amp;', '&', $inner);

            $html = substr_replace($html, $inner, $pBegin + 6, $pEnd - $pBegin - 6);

            $pBegin += strlen($inner) + 6;
        }

        return $html;
    }

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
            $render = new \Sundown\Render\HTML();
            $render->setRenderFlags(array
            (
                'filter_html' => true,
                'no_styles'   => true,
                'xhtml'       => true,
                'hard_wrap'   => true
            ));

            self::$md = new \Sundown\Markdown($render, array('no_intra_emphasis' => true));
        }

        return \VJ\Escaper::purify(self::_fixCodeBlock(self::$md->render(\VJ\Escaper::html($content))));

    }

}