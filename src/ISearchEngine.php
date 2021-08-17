<?php

namespace BABA\Search;

/**
 *
 * @author jpuchky
 */
interface ISearchEngine  {

    public function search($keyword, $language, $location, $num, $from);
    public function collectResultData($keyword, $language, $num, $from);
    public function getName();
    public function getConfigFile();
    public function getConfig();
    public function isAuthenticated();
    public function authentication($default = './tokens/auth.ini');
    public function getSuggestions($param, $languageId, $locationId, $opts):array;
    public function getResults($keyword, $language, $location, $opts):array;
    public function getVolume($param, $languageId, $locationIds, $opts):array;

}
