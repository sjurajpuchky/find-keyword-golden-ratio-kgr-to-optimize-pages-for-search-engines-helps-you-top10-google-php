<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class Volume extends Analyzer implements IAnalyzer
{
    public function getResult($param, $language,$location, $opts = [])
    {
        if($this->engine->isAuthenticated()) {
            return $this->engine->getVolume($param, $language, $location, $opts);
        } else {
            throw new \Exception("You must be authenticated to service of {$this->engine->getName()}.");
        }
    }
}