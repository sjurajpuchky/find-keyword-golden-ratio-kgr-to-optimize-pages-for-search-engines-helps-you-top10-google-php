<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\KGR;
use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new \BABA\Cache\Cache(new \BABA\Cache\Drivers\Disk());

if($engine->authentication(__DIR__.'/google-ads.ini')) {
    $keyword = 'zdravi';
    try {
        $results = (new KGR($engine,$cache))->getResult([$keyword], 1021, [2203], ['number-results' => 100, 'max-volume' => 250, 'min-volume' => 10,'max-ratio' => 0.25]);
        var_dump($results);
    } catch(Exception $e) {
        echo "{$e->getMessage()}\n";
    }
}
