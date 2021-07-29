<?php

require_once __DIR__.'/../vendor/autoload.php';

$engine = new \BABA\Search\Engines\Google();
$keyword = 'test';
$results = (new \BABA\Search\Analyzers\Results($engine))->getResult($keyword, 'cs');
echo "Results: {$results} for {$keyword}\n";