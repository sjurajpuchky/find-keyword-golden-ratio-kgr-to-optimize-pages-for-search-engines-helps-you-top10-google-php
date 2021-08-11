<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Search\Analyzers\KGR;
use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new \BABA\Cache\Cache(new \BABA\Cache\Drivers\Disk());
$list = [];
$volumes = [];
if($argc > 1) {
    $fp = fopen($argv[1], 'r');
    $head = fgetcsv($fp, 10000, ";");
    $keywords = [];
    while (($line = fgetcsv($fp, 1000, ";")) !== FALSE) {
        $keywords[$line[0]] = $line[1];
    }
    fclose($fp);
    foreach ($keywords as $keyword => $volume) {
        if($volume <= 250) {
            echo "Checking $keyword ($volume)...";
            $results = (new Results($engine, $cache))->getResult($keyword, 'lang_cs', ['cs'], ['allintitle' => true]);
            if (KGR::isKgr($results, $volume)) {
                $list[$keyword] = $results / $volume;
                $volumes[$keyword] = $volume;
                if($results == 1) {
                    echo "Kgr with {$list[$keyword]} and {$results} result found\n";
                } else {
                    echo "Kgr with {$list[$keyword]} and {$results} results found\n";
                }
            } else {
                if($volume == 0) {
                    echo "Potentialy future kgr\n";
                } else {
                    echo "Not kgr\n";
                }
            }
        } else {
            echo "Ignoring $keyword for $volume\n";
        }
    }

    $t = 0;
    foreach($list as $keyword => $kgr) {
        echo "$keyword:$kgr:".$volumes[$keyword]."\n";
        $t += (1/$kgr) * $volumes[$keyword];
     }
    echo "Totally potential trafic $t\n";
} else {
    echo "Usage: php kgr-csv.php <csv file>\n";
}