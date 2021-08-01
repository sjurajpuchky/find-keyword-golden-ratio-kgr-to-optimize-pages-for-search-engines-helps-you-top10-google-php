<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\Suggestion;
use BABA\Search\Engines\Google;


$engine = new Google();
if($engine->authentication()) {
    $keyword = 'test';
    try {
        $results = (new Suggestion($engine))->getResult([$keyword], 1021, [2203], ['number-results' => 100, 'max-volume' => 1000, 'min-volume' => 100]);
        var_dump($results);
    } catch(Exception $e) {
        echo "{$e->getMessage()}\n";
    }
}
