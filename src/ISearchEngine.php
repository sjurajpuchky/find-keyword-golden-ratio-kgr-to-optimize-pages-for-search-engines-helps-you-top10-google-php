<?php

namespace BABA\Search;

/**
 *
 * @author jpuchky
 */
interface ISearchEngine {

    public function search($keyword, $language, $num, $from);
    public function collectResultData($keyword, $language, $num, $from);
}
