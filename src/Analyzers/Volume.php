<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;
use Exception;

class Volume extends Analyzer implements IAnalyzer
{
    public function getResult($param, $language, $locationIds = [], $opts = [])
    {
        $cacheKey = $this->prepareCacheKey($keywords, $language, $locationIds);
        if ($this->isCached($cacheKey)) {
            return $this->loadFromCache($cacheKey);
        } else {
            if ($this->engine->isAuthenticated()) {
                $result = $this->engine->getVolume($param, $language, $locationIds, $opts);
                $this->storeInCache($cacheKey, $result);
                return $result;
            } else {
                throw new Exception("You must be authenticated to service of {$this->engine->getName()}.");
            }
        }
    }

    public function getName()
    {
        return self::class;
    }
}