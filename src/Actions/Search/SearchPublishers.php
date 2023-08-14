<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchPublishers
{
    use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%");
             return $this;
    }
}