<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;
use BABA\Search\Engine;
use DOMDocument;
use DOMXPath;

class Results extends Analyzer implements IAnalyzer
{

    public function getResult($keyword, $language,$location, $opts = [])
    {
         return  $this->engine->getResults($keyword, $language, $location, $opts);
    }

}