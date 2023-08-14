<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchCategories
{ use SearchHelper;

    private function searchTerms()
    {
        $this->queryBuilder
            ->where('name', 'like', "%$this->searchParam%");
        return $this;
    }

}