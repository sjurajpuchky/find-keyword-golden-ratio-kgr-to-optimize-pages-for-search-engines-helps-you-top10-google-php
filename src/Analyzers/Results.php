<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class Results extends Analyzer implements IAnalyzer
{

    public function getResult($keyword, $language, $location = [], $opts = [])
    {
        $cacheKey = $this->prepareCacheKey($keyword, $language, $location);

        if ($this->isCached($cacheKey)) {
            return $this->loadFromCache($cacheKey);
        } else {
            if(isset($opts['allintitle']) && $opts['allintitle']) {
                $keyword = 'allintitle:'.$keyword;
            }
            $result = $this->engine->getResults($keyword, $language, $location, $opts);
            $this->storeInCache($cacheKey, $result);
            return $result;
        }
    }

    public function getName()
    {
        return self::class;
    }

}