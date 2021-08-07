<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\KGR;
use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new \BABA\Cache\Cache(new \BABA\Cache\Drivers\Disk());
$list = [];
if($argc > 1) {
    $fp = fopen($argv[1], 'r');
    $head = fgetcsv($fp, 10000, ";");
    $keywords = [];
    while (($line = fgetcsv($fp, 1000, ";")) !== FALSE) {
        $keywords[$line[0]] = $line[1];
    }
    fclose($fp);
    foreach ($keywords as $keyword => $volume) {
        $results = (new Results($engine,$cache))->getResult($keyword, 'lang_cs',['cs'],[]);
        echo "Checking $keyword ($results)\n";
        if (KGR::isKgr($results, $volume)) {
            $list[$keyword] = $results / $volume;
            echo "Found kgr $keyword\n";
        }
    }
    var_dump($list);
} else {
    echo "Usage: php kgr-csv.php <csv file>\n";
}