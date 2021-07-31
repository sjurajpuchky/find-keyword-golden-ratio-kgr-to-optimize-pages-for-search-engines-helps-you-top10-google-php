<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$keyword = 'test';
$results = (new Results($engine))->getResult($keyword, 'lang_cs');
echo "Results: {$results} for {$keyword}\n";