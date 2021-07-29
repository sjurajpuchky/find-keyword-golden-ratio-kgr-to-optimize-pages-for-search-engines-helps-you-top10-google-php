<?php


namespace BABA\Search;


class Analyzer
{
    /** @var ISearchEngine */
    protected $engine;

    /**
     * Analyzer constructor.
     * @param ISearchEngine $engine
     */
    public function __construct(ISearchEngine $engine)
    {
        $this->engine = $engine;
    }

}