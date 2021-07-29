<?php


namespace BABA\Search\Analyzers;


class GKR implements Analyzer
{
    /** @var ISearch */
    private $engine;

    /**
     * Position constructor.
     */
    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function getPossition($keyword, $domain, $language, $num = 100, $from = 0)
    {
        $urls = $this->engine->search($keyword, $language, $num, $from);
        $position = 1;
        foreach($urls as $url) {
            if(preg_match('/'.addslashes($domain).'/',$url)) {
                break;
            }
            $position++;
        }

        return $position;
    }
}