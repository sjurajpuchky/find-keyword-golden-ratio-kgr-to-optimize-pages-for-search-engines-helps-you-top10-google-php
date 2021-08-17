<?php


namespace BABA\Search\Engines;


use BABA\Search\Engine;
use Exception;

class Collabim extends Google implements ISearchEngine
{
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
            throw new Exception("apiKey is required in $configFile section [COLLABIM], you can request it on www.collabim.cz\n");
        }

        $this->config = $ini;
        $this->configFile = $configFile;

        return $this->authenticated = true;
    }

    public function getSuggestions($param, $languageId, $locationIds, $opts): array
    {
    }

    public function getName()
    {
        return "Collabim";
    }


}