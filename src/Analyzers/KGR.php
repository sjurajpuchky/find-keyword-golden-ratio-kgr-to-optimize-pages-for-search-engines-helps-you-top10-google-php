<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class KGR extends Analyzer implements IAnalyzer
{
    public function getResult($keywords, $language, $locationIds = [], $opts = [])
    {

        $cacheKey = $this->prepareCacheKey($keywords, $language, $locationIds);
        if ($this->isCached($cacheKey)) {
            return $this->loadFromCache($cacheKey);
        } else {
            $suggestions = (new Suggestion($this->engine))->getResult($keywords, $language, $locationIds, ['number-results' => 100, 'max-volume' => $opts['max-volume'], 'min-volume' => $opts['min-volume']]);
            $count = 0;
            $result = [];
            foreach ($suggestions as $keyword => $suggestion) {
                $volume = $suggestion['volume'];
                $numberOfResults = (new Results($this->engine))->getResult($keyword, $language, $locationIds, []);
                $kgr = $numberOfResults / $volume;
                if ($kgr <= 0.25) {
                    $result[$keyword] = $kgr;
                }
                $count++;
                if ($count >= $opts['number-results']) {
                    break;
                }
            }
            $this->storeInCache($cacheKey, $result);
            return $result;
        }
    }

    public static function isKgr($numberOfResults, $volume)
    {
        return ($numberOfResults / $volume) <= 0.25;
    }

    public function getName()
    {
        return self::class;
    }


}