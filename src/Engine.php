<?php

namespace BABA\Search;

use DOMDocument;

/**
 * Description of Search
 *
 * @author jpuchky
 */
class Engine extends AbstractEngine {


    /**
     * @param $data
     * @return DOMDocument
     */
    public static function prepareDom($data) {
        libxml_use_internal_errors(true);
        $urls = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        libxml_clear_errors();

        return $dom;
    }

    private static function write_cookies(ISearchEngine $engine, $cookies)
    {
        file_put_contents($engine->getName().'.txt', implode("\n",$cookies));
    }

    public function getCookies($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->getName().'.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE,$this->getName().'.txt');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        $result = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        return $cookies;
    }
    /**
     * @param $url
     * @return bool|string
     */
    public function getData($url,$userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->getName().'.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE,$this->getName().'.txt');


        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        return curl_exec($ch);
    }

    /**
     * @param $url
     * @return string
     */
    public function normalizeUrl($url) {
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

    public static function parseIni($configFile) {
        $ini = parse_ini_file($configFile,true);

        if(!$ini) {
            throw new Exception("Error parsing $configFile");
        }

        return $ini;
    }

    public static function storeIni($configFile, $ini) {
        if(file_exists($configFile)) {
            rename($configFile, $configFile . '.old-' . time());
        }
        foreach($ini as $section => $values) {
            file_put_contents($configFile,'['.$section.']'."\n",FILE_APPEND);
            foreach($values as $name => $value) {
                file_put_contents($configFile,$name.' = "'.$value.'"'."\n",FILE_APPEND);
            }
        }
    }
}
