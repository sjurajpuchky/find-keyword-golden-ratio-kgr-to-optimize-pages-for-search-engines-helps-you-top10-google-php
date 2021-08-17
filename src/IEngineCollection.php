<?php


namespace BABA\Search;


interface IEngineCollection
{
    public function addRecord(ISearchEngine $record);
}