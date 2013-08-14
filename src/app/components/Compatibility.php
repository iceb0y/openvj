<?php

namespace VJ;

class Compatibility
{
    /**
     * 检查并将Vijos1的地址跳转到新地址
     */
    public static function redirectOldURI()
    {
        global $__CONFIG;

        // Check whether the requested URI is an old-style .asp URI. If it is,
        // redirect to a corresponding new URI
        if (stripos($_SERVER['REQUEST_URI'], '.asp') !== false) {
            if (!isset($_SERVER['HTTP_USER_AGENT']) ||
                stripos($_SERVER['HTTP_USER_AGENT'], 'Baiduspider') === false &&
                stripos($_SERVER['HTTP_USER_AGENT'], 'Sogou web spider') === false &&
                stripos($_SERVER['HTTP_USER_AGENT'], 'Sosospider') === false
            ) {
                $prefix = 'https://';
            } else {
                $prefix = 'http://';
            }

            header('HTTP/1.1 301 Moved Permanently');

            $uri  = $_SERVER['REQUEST_URI'];
            $host = $__CONFIG->Misc->host;

            if (stripos($uri, '/problem_show.asp') !== false) {
                header('Location: '.$prefix.$host.'/p/'.$_GET['id']);
            } else if (stripos($uri, '/user_show.asp') !== false) {
                header('Location: '.$prefix.$host.'/user/'.$_GET['id']);
            } else if (stripos($uri, '/problem_discuss.asp') !== false) {
                header('Location: '.$prefix.$host.'/p/'.$_GET['id']);
            } else if (stripos($uri, '/problem_discuss_show.asp') !== false) {
                header('Location: '.$prefix.$host.'/p/'.$_GET['id']);
            } else if (stripos($uri, '/problem2.asp') !== false) {
                header('Location: '.$prefix.$host.'/p');
            } else {
                header('Location: '.$prefix.$host);
            }

            return;
        }
    }

}