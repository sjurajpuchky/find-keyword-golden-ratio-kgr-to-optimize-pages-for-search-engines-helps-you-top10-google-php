<?php


namespace BABA\Search;


use BABA\Cache\Cache;

class Analyzer
{
    /** @var ISearchEngine $engine */
    protected $engine;
    /** @var Cache $cache */
    private $cache;

    /**
     * Analyzer constructor.
     * @param ISearchEngine $engine
     * @param Cache $cache
     */
    public function __construct(ISearchEngine $engine, Cache $cache)
    {
        $this->engine = $engine;
        $this->cache = $cache;
    }

    public function prepareCacheKey($keywords, $language, $locations): string
    {
        if (is_array($keywords)) {
            $cacheKey = $this->engine->getName().'_'.$this->getName() . '_' . $language . '_' . implode('-', $locations) . '_' . implode('-', $keywords);
        } else {
            $cacheKey = $this->engine->getName().'_'.$this->getName() . '_' . $language . '_' . implode('-', $locations) . '_' . $keywords;
        }

        return $cacheKey;
    }

    protected function storeInCache($cacheKey, $results)
    {
        $this->cache->store($cacheKey, $results);
    }

    protected function loadFromCache($cacheKey)
    {
        return $this->cache->load($cacheKey);
    }

    protected function isCached($cacheKey)
    {
        return $this->cache->isValid($cacheKey);
    }
}