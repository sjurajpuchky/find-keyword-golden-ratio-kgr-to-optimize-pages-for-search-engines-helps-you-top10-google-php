<?php

include_once __DIR__.'/../vendor/autoload.php';

use BABA\Cache\Cache;
use BABA\Cache\Drivers\Disk;
use BABA\Search\Analyzers\KGR;
use BABA\Search\Analyzers\Results;
use BABA\Search\Engines\Google;


$engine = new Google();
$cache = new Cache(new Disk(),24*3600);
$list = [];
$statuses = [];
$volumes = [];
$foundResults = [];
if ($argc > 2) {
    $fp = fopen($argv[1], 'r');
    $head = fgetcsv($fp, 10000, ";");
    $keywords = [];
    while (($line = fgetcsv($fp, 1000, ";")) !== FALSE) {
        $keywords[$line[0]] = $line[1];
    }
    fclose($fp);
    foreach ($keywords as $keyword => $volume) {
        $volumes[$keyword] = $volume;
        if ($volume <= 250 && $volume > 0) {
            echo "Checking $keyword ($volume)...";
            $results = (new Results($engine, $cache))->getResult($keyword, 'lang_cz', ['cz'], ['allintitle' => true]);
            $foundResults[$keyword] = $results;

            if (KGR::isKgr($results, $volume)) {
                $list[$keyword] = $results / $volume;
                $statuses[$keyword] = 'kgr';
                if($results == 1) {
                    echo "Kgr with {$list[$keyword]} and {$results} result found\n";
                } else {
                    echo "Kgr with {$list[$keyword]} and {$results} results found\n";
                }
            } else {
                $statuses[$keyword] = 'not kgr';
                    echo "Not kgr\n";
            }
        } else {
            if($volume == 0) {
                $statuses[$keyword] = 'potentially future keyword';
                echo "Potentially future kgr\n";
            } else {
                $statuses[$keyword] = "ignored because of volume";
                echo "Ignoring $keyword for $volume\n";
            }
        }
    }

    $t = 0;
    $content = "keyword;kgr;volume;results;status\n";
    foreach ($keywords as $keyword => $volume) {
        $kgr = isset($list[$keyword]) ? $list[$keyword] : -1;
        $results = isset($foundResults[$keyword]) ? $foundResults[$keyword] : -1;
        $content .= "\"$keyword\";{$kgr};$volume;$results;{$statuses[$keyword]}\n";
        if ($kgr == 0) {
            $t += $volume;
        } elseif($kgr > 0) {
            $t += $kgr * $volume;
        }
    }
    file_put_contents($argv[2], $content);
    echo "Totally potential traffic $t\n";
    $t = 0;
    foreach ($statuses as $keyword => $status) {
        if($status == "potentially future keyword") {
            $t++;
        }
    }
    echo "Found {$t} future keywords\n";
} else {
    echo "Usage: php kgr-csv.php <csv file> <out csv file>\n";
}