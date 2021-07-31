<?php


namespace BABA\Search\Analyzers;

use BABA\Search\Analyzer;
use BABA\Search\IAnalyzer;
use BABA\Search\Engine;
use DOMDocument;
use DOMXPath;

class Results extends Analyzer implements IAnalyzer
{

    public function getResult($keyword, $language, $opts = [])
    {
        $dom = Engine::prepareDom($this->engine->collectResultData($keyword, $language,10,0));
        $xpath = new DOMXPath($dom);
        $elm = $xpath->query("//*/div[@id='result-stats']")->item(0);
        if($elm) {
            $result = $elm->textContent;
            $result = explode('(',explode(':',$result)[1])[0];
            return str_replace([' '," "],'',$result);
        }
        return 0;
    }

}