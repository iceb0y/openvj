<?php

namespace VJ\IO;

use \VJ\I;

class Utils
{

    public static function curl($host, $port, $timeout, $URI, $get = null, $post = null)
    {

        $url = $host.$URI;

        if ($get != null && is_array($get)) {
            $url = $url.'?'.\VJ\Escaper::uriQuery($get);
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PORT           => $port,
            CURLOPT_HTTPHEADER     => ['content-type: application/json'],
            CURLOPT_NOBODY         => false
        ]);

        if ($post != null && is_array($post)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, \VJ\Escaper::json($post));
        }

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \VJ\Exception('ERR_CURL_ERROR', curl_errno($curl), curl_error($curl));
        }

        curl_close($curl);

        return $data;

    }

}