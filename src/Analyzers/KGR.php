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
            $suggestions = (new Suggestion($this->engine,$this->cache))->getResult($keywords, $language, $locationIds, ['number-results' => 100, 'max-volume' => $opts['max-volume'], 'min-volume' => $opts['min-volume']]);
            $count = 0;
            $result = [];
            foreach ($suggestions as $keyword => $suggestion) {
                $volume = $suggestion['volume'];
                $numberOfResults = (new Results($this->engine,$this->cache))->getResult($keyword, $language, $locationIds, ['allintitle' => true]);
                if(self::isKgr($numberOfResults, $volume)) {
                    $result[$keyword] = ($numberOfResults / $volume);
                }
                $count++;
                if ($count >= $opts['number-results']) {
                    break;
                }
            }
            $result  = sort($result);
            $this->storeInCache($cacheKey, $result);
            return $result;
        }
    }

    public static function isKgr($numberOfResults, $volume)
    {
        if($volume > 250) {
            return false;
        }
        return ($numberOfResults / $volume) <= 0.25;
    }

    public function getName()
    {
        return self::class;
    }


}