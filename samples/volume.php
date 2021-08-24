<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\Volume;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new \BABA\Cache\Cache(new \BABA\Cache\Drivers\Disk());

if($engine->authentication(__DIR__.'/google-ads.ini')) {
    $keyword = 'test';
    try {
        $results = (new Volume($engine,$cache))->getResult([$keyword], 1021, [2203], ['number-results' => 100, 'max-volume' => 1000, 'min-volume' => 100]);
        var_dump($results);
    } catch(Exception $e) {
        echo "{$e->getMessage()}\n";
    }
}
