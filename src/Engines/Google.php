<?php

namespace BABA\Search\Engines;

use BABA\Search\Engine;
use BABA\Search\ISearchEngine;
use DOMXPath;
use Exception;
use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V8\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V8\Services\KeywordSeed;
use Google\Ads\GoogleAds\V8\Services\UrlSeed;
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

/**
 * Description of Google
 *
 * @author jpuchky
 */
class Google extends Engine implements ISearchEngine
{

    const SEARCH_URL = 'https://www.google.com/search?q=';
    private $config = [];
    private $authenticated = false;
    private $configFile = './tokens/google-ads.ini';

    /**
     * @param $keyword
     * @param $language
     * @param $num
     * @param int $from
     * @return array
     */
    public function search($keyword, $language, $location, $num, $from = 0): array
    {
        $urls = [];
        $dom = self::prepareDom($this->collectResultData($keyword, $language, $location, $num, $from));
        $xp = new DOMXPath($dom);
        $results = $xp->query('//*/div[@class="kCrYT"]');
        foreach ($results as $r) {
            $as = $r->getElementsByTagName('a');
            foreach ($as as $a) {
                if (strstr($a->getAttribute('href'), '/url?q=')) {
                    $urls[$a->getAttribute('href')] = preg_replace('/^\/url\?q=/', '', $a->getAttribute('href'));
                }
            }
        }
        return $urls;
    }

    /**
     * @param $keyword
     * @param $language
     * @param $num
     * @param int $from
     * @return string|null
     */
    public function collectResultData($keyword, $language, $location, $num, $from = 0): ?string
    {
        return $this->getData(self::SEARCH_URL . urlencode($keyword) . "&oq=" . urlencode($keyword) . "&ie=UTF-8&num=$num&hl=$language&start=$from");
    }

    public function getName()
    {
        return "Google";
    }

    /**
     * @var string the OAuth2 scope for the Google Ads API
     * @see https://developers.google.com/google-ads/api/docs/oauth/internals#scope
     */
    private const SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     * @see https://developers.google.com/identity/protocols/oauth2/native-app#step-2:-send-a-request-to-googles-oauth-2.0-server
     */
    private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the redirect URI for OAuth2 Desktop application flows
     * @see https://developers.google.com/identity/protocols/oauth2/native-app#request-parameter-redirect_uri
     */
    private const REDIRECT_URI = 'urn:ietf:wg:oauth:2.0:oob';

    /**
     * @param string $configFile
     * @return bool
     * @throws Exception
     */
    public function authentication($configFile = './tokens/google-ads.ini'): bool
    {
        if (!file_exists($configFile)) {
            throw new Exception("$configFile does not exists.");
        }

        $ini = Engine::parseIni($configFile);

        if (empty($ini['GOOGLE_ADS']['developerToken'])) {
            throw new Exception("developerToken is required in $configFile section [GOOGLE_ADS], you can request it on ads.google.com with MMC account\n");
        }
        if (empty($ini['GOOGLE_ADS']['clientCustomerId'])) {
            throw new Exception("clientCustomerId is required in $configFile section [GOOGLE_ADS], you can request it on ads.google.com with MMC account\n");
        }
        if (empty($ini['OAUTH2']['clientId'])) {
            throw new Exception("clientId is required in $configFile section [OAUTH2], you can request in on https://console.cloud.google.com/apis/credentials/oauthclient, register your credentials as Desktop application\n");
        }
        if (empty($ini['OAUTH2']['clientSecret'])) {
            throw new Exception("clientSecret is required in $configFile section [OAUTH2], you can request in on https://console.cloud.google.com/apis/credentials/oauthclient, register your credentials as Desktop application\n");
        }
        if (empty($ini['OAUTH2']['refreshToken'])) {

            $stdin = fopen('php://stdin', 'r');

            $oauth2 = new OAuth2(
                [
                    'authorizationUri' => self::AUTHORIZATION_URI,
                    'redirectUri' => self::REDIRECT_URI,
                    'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                    'clientId' => $ini['OAUTH2']['clientId'],
                    'clientSecret' => $ini['OAUTH2']['clientSecret'],
                    'scope' => self::SCOPE,
                ]
            );

            printf(
                'Log into the Google account you use for Google Ads and visit the following URL:'
                . '%1$s%2$s%1$s%1$s',
                PHP_EOL,
                $oauth2->buildFullAuthorizationUri()
            );
            print 'After approving the application, enter the authorization code here: ';
            $code = trim(fgets($stdin));
            $oauth2->setCode($code);
            $authToken = $oauth2->fetchAuthToken();
            fclose($stdin);
            if (isset($authToken['refresh_token']) && !empty($authToken['refresh_token'])) {
                $ini['OAUTH2']['refreshToken'] = $authToken['refresh_token'];
                Engine::storeIni($configFile, $ini);
            } else {
                throw new Exception("Authentication failed.");
            }
        }
        $this->config = $ini;
        $this->configFile = $configFile;

        return $this->authenticated = true;
    }

    /**
     * @return string
     */
    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * @param $param
     * @param $languageId
     * @param $locationIds
     * @param $opts
     * @return array
     * @throws Exception
     */
    public function getVolume($param, $languageId, $locationIds, $opts): array
    {
        if (is_null($languageId)) {
            throw new Exception("Check for language criteria id on https://developers.google.com/adwords/api/docs/appendix/codes-formats#languages");
        }
        if (is_null($locationIds) || empty($locationIds)) {
            throw new Exception("Check for location criteria id on https://developers.google.com/adwords/api/docs/appendix/geotargeting");
        }

        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($this->getConfigFile())->build();
        $googleAdsClient = (new GoogleAdsClientBuilder())->fromFile($this->getConfigFile())
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        $keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();

        if (empty($param)) {
            throw new \InvalidArgumentException(
                'At least one of keywords or page URL is required, but neither was specified.'
            );
        }

        $requestOptionalArgs = [];
        if (!is_array($param)) {
            $requestOptionalArgs['urlSeed'] = new UrlSeed(['url' => $param]);
        } else {
            $requestOptionalArgs['keywordSeed'] = new KeywordSeed(['keywords' => $param]);
        }


        $geoTargetConstants = array_map(function ($locationId) {
            return ResourceNames::forGeoTargetConstant($locationId);
        }, $locationIds);


        $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas(
            [

                'language' => ResourceNames::forLanguageConstant($languageId),
                'customerId' => $this->config['GOOGLE_ADS']['clientCustomerId'],
                'geoTargetConstants' => $geoTargetConstants,
                'keywordPlanNetwork' => KeywordPlanNetwork::GOOGLE_SEARCH_AND_PARTNERS
            ] + $requestOptionalArgs
        );
        $results = [];
        $i = 0;
        foreach ($response->iterateAllElements() as $result) {
            /** @var GenerateKeywordIdeaResult $result */
            if ($opts['max-volume'] >= is_null($result->getKeywordIdeaMetrics()) ? 0 : ($result->getKeywordIdeaMetrics()->getAvgMonthlySearches() && $opts['min-volume'] <= is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches())) {
                $results[$result->getText()] = [
                    'mothly' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches(),
                    'competition' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getCompetition()];

                $i++;
            }
            if ($i > $opts['number-results']) {
                break;
            }
        }

        return $results;
    }

    /**
     * @param $param
     * @param $languageId
     * @param $locationIds
     * @param $opts
     * @return array
     * @throws Exception
     */
    public function getSuggestions($param, $languageId, $locationIds, $opts): array
    {
        if (is_null($languageId)) {
            throw new Exception("Check for language criteria id on https://developers.google.com/adwords/api/docs/appendix/codes-formats#languages");
        }
        if (is_null($locationIds) || empty($locationIds)) {
            throw new Exception("Check for location criteria id on https://developers.google.com/adwords/api/docs/appendix/geotargeting");
        }

        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($this->getConfigFile())->build();
        $googleAdsClient = (new GoogleAdsClientBuilder())->fromFile($this->getConfigFile())
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        $keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();

        if (empty($param)) {
            throw new \InvalidArgumentException(
                'At least one of keywords or page URL is required, but neither was specified.'
            );
        }

        $requestOptionalArgs = [];
        if (!is_array($param)) {
            $requestOptionalArgs['urlSeed'] = new UrlSeed(['url' => $param]);
        } else {
            $requestOptionalArgs['keywordSeed'] = new KeywordSeed(['keywords' => $param]);
        }


        $geoTargetConstants = array_map(function ($locationId) {
            return ResourceNames::forGeoTargetConstant($locationId);
        }, $locationIds);


        $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas(
            [

                'language' => ResourceNames::forLanguageConstant($languageId),
                'customerId' => $this->config['GOOGLE_ADS']['clientCustomerId'],
                'geoTargetConstants' => $geoTargetConstants,
                'keywordPlanNetwork' => KeywordPlanNetwork::GOOGLE_SEARCH_AND_PARTNERS
            ] + $requestOptionalArgs
        );
        $results = [];
        $i = 0;
        foreach ($response->iterateAllElements() as $result) {
            /** @var GenerateKeywordIdeaResult $result */
            if ($opts['max-volume'] >= is_null($result->getKeywordIdeaMetrics()) ? 0 : ($result->getKeywordIdeaMetrics()->getAvgMonthlySearches() && $opts['min-volume'] <= is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches())) {
                $results[$result->getText()] = [
                    'mothly' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches(),
                    'competition' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getCompetition()];

                $i++;
            }
            if ($i > $opts['number-results']) {
                break;
            }
        }

        return $results;
    }

    /**
     * @param $keyword
     * @param $language
     * @param $location
     * @param $opts
     * @return int|string|string[]
     */
    public function getResults($keyword, $language, $location, $opts)
    {
        $dom = Engine::prepareDom($this->collectResultData($keyword, $language, $location, 10, 0));
        $xpath = new DOMXPath($dom);
        $elm = $xpath->query("//*/div[@id='result-stats']")->item(0);
        if ($elm) {
            $result = $elm->textContent;
            if (!empty($result)) {
                $result = explode('(', explode(':', $result)[1])[0];
                return str_replace([' ', " "], '', $result);
            } else {
                return 0;
            }
        }

        return 0;
    }
}
