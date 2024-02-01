<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchBanks
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%");
                });

        return $this;
    }
}