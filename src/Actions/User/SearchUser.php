<?php

namespace Transave\ScolaBookstore\Actions\User;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchUser
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder = $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%")
                ->orWhere('department', 'like', "%$search%")
                ->orWhere('specialization', 'like', "%$search%")
                ->orWhere('faculty', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
        });

        return $this;
    }
}