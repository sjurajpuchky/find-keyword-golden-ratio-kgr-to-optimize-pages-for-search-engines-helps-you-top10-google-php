<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class Position extends Analyzer implements IAnalyzer
{
    public function getResult($keywords, $language, $locationIds = [], $opts = ['num' => 100, 'from' => 0, 'domain' => ''])
    {
        $cacheKey = $this->prepareCacheKey($keywords, $language, $locationIds);
        if ($this->isCached($cacheKey)) {
            return $this->loadFromCache($cacheKey);
        } else {
            $urls = $this->engine->search($keyword, $language, $location, $opts['num'], $opts['from']);
            $position = 1;
            foreach ($urls as $url) {
                if (preg_match('/' . addslashes($opts['domain']) . '/', $url)) {
                    break;
                }
                $position++;
            }
            $this->storeInCache($cacheKey, $position);
            return $position;
        }
    }

    public function getName()
    {
        return self::class;
    }

}