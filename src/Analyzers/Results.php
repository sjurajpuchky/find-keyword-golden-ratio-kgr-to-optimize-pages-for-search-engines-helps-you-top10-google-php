<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\ISearchEngine;
use BABA\Search\IAnalyzer;
use DOMDocument;
use DOMXPath;

class Results extends Analyzer implements IAnalyzer
{

    public function getResult($keyword, $language, $opts = [])
    {
        libxml_use_internal_errors(true);
        $urls = [];
        $data = $this->engine->collectResultData($keyword, $language,1,0);
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
        $elm = $xpath->query("div[id='result-stats']")->item(0);
        if($elm) {
            $result = $elm->textContent;
            $result = explode('(',explode(':',$result)[1])[0];
            return str_replace(' ','',$result);
        }
        return 0;
    }

}