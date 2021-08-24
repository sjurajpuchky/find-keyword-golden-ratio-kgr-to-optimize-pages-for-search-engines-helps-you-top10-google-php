<?php


namespace BABA\Search\Engines;


use BABA\Search\Engine;
use BABA\Search\ISearchEngine;
use Exception;

class Collabim extends Google implements ISearchEngine
{
    /** @var \BABA\Collabim\API\Client\Collabim */
    private $client;

    /**
     * Collabim constructor.
     */
    public function __construct()
    {
        $this->client = new \BABA\Collabim\API\Client\Collabim();
    }

    /**
     * @param string $configFile
     * @return bool
     * @throws Exception
     */
    public function authentication($configFile = './tokens/collabim.ini'): bool
    {
        if (!file_exists($configFile)) {
            throw new Exception("$configFile does not exists.");
        }

        $ini = Engine::parseIni($configFile);

        if (empty($ini['COLLABIM']['apiKey'])) {
            throw new Exception("apiKey is required in $configFile section [COLLABIM], you can request it on https://www.collabim.com/?promoCode=mRfeciXH1V\n");
        }

        $this->config = $ini;
        $this->configFile = $configFile;

        return $this->authenticated = $this->client->authenticate($ini['COLLABIM']['apiKey']);
    }

    /**
     * @param $param string[] keywords
     * @param $languageId string ignored, language code is specified by Search Engine $opts['search-engine']
     * @param $locationIds int[] see doc below
     * @param $opts
     * @return array
     * @throws Exception
     * Available Google GEO location codes: https://goo.gl/t7UAww
     * Available Search engines: https://goo.gl/mKTAZB
     */
    public function getVolume($param, $languageId, $locationIds, $opts): array
    {
        if (is_array($param) && empty($param)) {
            throw new Exception('Specify at least one keyword');
        } elseif (!is_array($param)) {
            $param = [$param];
        }

        if (!isset($locationIds[0])) {
            throw new Exception('locationIds[0] is mandatory');
        }
        if (!isset($opts['search-engine'])) {
            throw new Exception('opts[\'search-engine\'] is mandatory');
        }
        if (!isset($opts['priority'])) {
            $opts['priority'] = 1;
        }

        $results = $this->client->oneTimeAnalysesKeywordMeasuring($locationIds[0], $param, $opts['search-engine'], $opts['priority']);

        // TODO: Processing results
        $ret = [];

        return $ret;
    }


    public function getName()
    {
        return "Collabim";
    }


}