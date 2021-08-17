<?php


namespace BABA\Search;


use Exception;

class EngineEngineCollection extends AbstractEngine implements IEngineCollection
{
    /** @var array ISearchEngine */
    private $engines = [];

    /**
     * EngineEngineCollection constructor.
     * @param array $engines
     */
    public function __construct(array $engines = [])
    {
        $this->engines = $engines;
    }

    public function addRecord(ISearchEngine $record)
    {
        $this->engines[] = $record;
    }


    public function search($keyword, $language, $location, $num, $from = 0): array
    {
        $results = [];
        /** @var ISearchEngine $engine */
        foreach ($this->engines as $engine) {
            array_merge($results, $engine->search($keyword, $language, $location, $num, $from));
        }

        return $results;
    }

    public function getVolume($param, $languageId, $locationIds, $opts): array
    {
        // TODO: Implement getVolume() method.
    }

    public function getSuggestions($param, $languageId, $locationIds, $opts): array
    {
        // TODO: Implement getSuggestions() method.
    }

    public function getResults($keyword, $language, $location, $opts)
    {
        // TODO: Implement getResults() method.
    }


    /**
     * @param string $configFile
     * @return bool
     * @throws Exception
     */
    public function authentication($configFile = './tokens/auth.ini'): bool
    {
        /** @var ISearchEngine $engine */
        foreach ($this->engines as $engine) {
            if(empty($configFile)) {
                $engine->authentication();
            } else {
                $engine->authentication($configFile);
            }
        }
    }
}