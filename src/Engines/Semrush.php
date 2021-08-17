<?php


namespace BABA\Search\Engines;


use BABA\Search\Engine;

class Semrush extends Google  implements ISearchEngine
{
    public function getSuggestions($param, $languageId, $locationIds, $opts): array
    {
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

        if (empty($ini['SEMRUSH']['apiKey'])) {
            throw new Exception("apiKey is required in $configFile section [SEMRUSH], you can request it on www.semrush.com\n");
        }

        $this->config = $ini;
        $this->configFile = $configFile;

        return $this->authenticated = true;
    }
}