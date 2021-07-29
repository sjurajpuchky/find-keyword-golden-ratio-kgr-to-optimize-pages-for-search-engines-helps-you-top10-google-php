<?php


namespace BABA\Search;


use BABA\Search\ISearchEngine;

interface IAnalyzer
{
    public function getResult($keyword, $language, $opts = []);
}