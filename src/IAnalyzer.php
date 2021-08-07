<?php


namespace BABA\Search;


interface IAnalyzer
{
    public function getResult($keywords, $language, $locations = [], $opts = []);

    public function getName();
}