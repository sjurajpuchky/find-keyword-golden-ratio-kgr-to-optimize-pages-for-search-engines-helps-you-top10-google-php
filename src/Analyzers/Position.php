<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;

class Position extends Analyzer implements IAnalyzer
{
    public function getResult($keyword, $language, $location = [], $opts = ['num' => 100, 'from' => 0, 'domain' => ''])
    {
        $urls = $this->engine->search($keyword, $language, $location, $opts['num'], $opts['from']);
        $position = 1;
        foreach($urls as $url) {
            if(preg_match('/'.addslashes($opts['domain']).'/',$url)) {
                break;
            }
            $position++;
        }

        return $position;
    }
}