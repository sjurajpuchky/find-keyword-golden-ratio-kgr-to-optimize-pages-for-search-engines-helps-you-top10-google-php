<?php


namespace BABA\Search\Engines;


use BABA\Search\Engine;

class Kwfinder extends Google  implements ISearchEngine
{
    public function getSuggestions($param, $languageId, $locationIds, $opts): array
    {
    }
    /**
     * @param string $configFile
     * @return bool
     * @throws Exception
     */
    public function authentication($configFile = './tokens/kwfinder.ini'): bool
    {
        if (!file_exists($configFile)) {
            throw new Exception("$configFile does not exists.");
        }

        $ini = Engine::parseIni($configFile);

        if (empty($ini['KWFINDER']['apiKey'])) {
            throw new Exception("apiKey is required in $configFile section [KWFINDER], you can request it on https://kwfinder.com#a610ee61bfeebf87f1c28d2d6\n");
        }

        $this->config = $ini;
        $this->configFile = $configFile;

        return $this->authenticated = true;
    }
}