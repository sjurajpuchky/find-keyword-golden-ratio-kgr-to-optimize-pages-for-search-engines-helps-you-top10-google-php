<?php

namespace BABA\Search\Engines;

use BABA\Search\ISearchEngine;
use BABA\Search\Engine;
use DOMDocument;
use DOMXPath;



/**
 * Description of Google
 *
 * @author jpuchky
 */
class Yandex extends Engine implements ISearchEngine {

    private $url = 'https://www.google.com/search?q=';

    public function search($keyword, $language, $num, $from = 0) {
        libxml_use_internal_errors(true);
        $urls = [];
        $data = $this->getData($this->url . urlencode($keyword) . "&num=$num&hl=$language&start=$from");
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        libxml_clear_errors();
        $xp = new DOMXPath($dom);
        $results = $xp->query('//*/div[@class="kCrYT"]');
        foreach ($results as $r) {
            $as = $r->getElementsByTagName('a');
            foreach ($as as $a) {
                if (strstr($a->getAttribute('href'), '/url?q=')) {
                    $urls[$a->getAttribute('href')]=preg_replace('/^\/url\?q=/','',$a->getAttribute('href'));
                }
            }
        }
        return $urls;
    }
}
