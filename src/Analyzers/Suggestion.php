<?php


namespace BABA\Search\Analyzers;


use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;




class Suggestion extends Analyzer implements IAnalyzer
{
    const PAGE_LIMIT = 10;

    /**
     * @param $keywords string[]|string array of keywords or url of web page
     * @param $language int|null
     * @param $location int|null
     * @param array $opts ex. ['number-results' => 100,'max-volume' => 1000, 'min-volume' => 100]
     */
    public function getResult($param, $language = NULL, $location = NULL, $opts = ['number-results' => self::PAGE_LIMIT,'max-volume' => 1000, 'min-volume' => 100])
    {
        if($this->engine->isAuthenticated()) {
            return $this->engine->getSuggestions($param, $language, $location, $opts);
        } else {
            throw new \Exception("You must be authenticated to service of {$this->engine->getName()}.");
        }
    }
}