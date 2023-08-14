<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchSchools
{
    use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('faculty', 'like', "%$this->searchParam%")
            ->orwhere('department', 'like', "%$this->searchParam%");
        return $this;
    }
}