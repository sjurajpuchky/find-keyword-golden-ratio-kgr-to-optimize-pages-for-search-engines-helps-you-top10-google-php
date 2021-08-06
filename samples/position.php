<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\Position;
use BABA\Search\Engines\Google;


$engine = new Google();
$keyword = 'BABA Tumise s.r.o.';
$position = (new Position($engine))->getResult($keyword, 'lang_cs','',['num' => 100, 'from' => 0, 'domain' => 'baba.bj']);
echo "Result: {$position} for {$keyword}\n";