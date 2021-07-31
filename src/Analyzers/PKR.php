<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class PKR extends Analyzer implements IAnalyzer
{
    public function getResult($keyword, $language, $opts = [])
    {
        return 0;
    }
}