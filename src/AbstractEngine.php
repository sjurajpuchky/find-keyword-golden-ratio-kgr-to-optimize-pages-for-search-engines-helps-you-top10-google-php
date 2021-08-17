<?php


namespace BABA\Search;


use Exception;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V8\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V8\Services\KeywordSeed;
use Google\Ads\GoogleAds\V8\Services\UrlSeed;
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

abstract class AbstractEngine
{
    /**
     * @param $keyword
     * @param $language
     * @param $num
     * @param int $from
     * @return array
     */
    public abstract function search($keyword, $language, $location, $num, $from = 0): array;


    /**
     * @param string $configFile
     * @return bool
     * @throws Exception
     */
    public abstract function authentication($configFile = './tokens/auth.ini'): bool;

    /**
     * @param $param
     * @param $languageId
     * @param $locationIds
     * @param $opts
     * @return array
     * @throws Exception
     */
    public abstract function getVolume($param, $languageId, $locationIds, $opts): array;

    /**
     * @param $param
     * @param $languageId
     * @param $locationIds
     * @param $opts
     * @return array
     * @throws Exception
     */
    public abstract function getSuggestions($param, $languageId, $locationIds, $opts): array;

    /**
     * @param $keyword
     * @param $language
     * @param $location
     * @param $opts
     * @return int|string|string[]
     */
    public abstract function getResults($keyword, $language, $location, $opts);
}