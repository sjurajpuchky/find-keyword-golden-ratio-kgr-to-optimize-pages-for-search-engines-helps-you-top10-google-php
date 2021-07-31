<?php


namespace BABA\Search;


interface IAnalyzer
{
    public function getResult($keyword, $language, $opts = []);
}