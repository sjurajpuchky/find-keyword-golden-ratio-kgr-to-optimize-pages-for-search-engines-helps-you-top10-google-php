<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new \BABA\Cache\Cache(new \BABA\Cache\Drivers\Disk());
$keyword = 'test';
$results = (new Results($engine,$cache))->getResult($keyword, 'lang_cs');
echo "Results: {$results} for {$keyword}\n";