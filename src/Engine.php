<?php

namespace BABA\Search;

/**
 * Description of Search
 *
 * @author jpuchky
 */
class Engine {


    public static function prepareDom($data) {
        libxml_use_internal_errors(true);
        $urls = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        libxml_clear_errors();
    }

    public static function getData($url) {
        //sleep(1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';

        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        return curl_exec($ch);
    }

    public static function normalizeUrl($url) {
        $normalizedUrl = $url;

        if (preg_match('/https?:\/\/.+\//', $url)) {
            $normalizedUrl = $url;
        } else {
            if (preg_match('/.+\/$/', $url)) {
                if (preg_match('/https?:\/\//', $url)) {
                    $normalizedUrl = $url;
                } else {
                    $normalizedUrl = 'http://' . $url;
                }
            } else {
                if (preg_match('/https?:\/\//', $url)) {
                    $normalizedUrl = $url . '/';
                } else {
                    $normalizedUrl = 'http://' . $url . '/';
                }
            }
        }

        return $normalizedUrl;
    }
}
