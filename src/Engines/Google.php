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
class Google extends Engine implements ISearchEngine {

    const SEARCH_URL = 'https://www.google.com/search?q=';

    /**
     * @param $keyword
     * @param $language
     * @param $num
     * @param int $from
     * @return array
     */
    public function search($keyword, $language, $num, $from = 0)
    {
        $urls = [];
        $dom = self::prepareDom($this->collectResultData($keyword, $language, $num, $from));
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

    /**
     * @param $keyword
     * @param $language
     * @param $num
     * @param int $from
     * @return string
     */
    public function collectResultData($keyword, $language, $num, $from = 0)
    {
        return Engine::getData(self::SEARCH_URL . urlencode($keyword) . "&num=$num&hl=$language&start=$from");
    }
}
