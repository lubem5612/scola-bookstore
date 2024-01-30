<?php

namespace Transave\ScolaBookstore\Actions\Reviewer;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchReviewer
{
    use SearchHelper;

    private function searchTerms()
    {        
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('specialization', 'like', "%$search%")
                ->orWhere('year_of_project', 'like', "%$search%") 
                ->orWhere('status', 'like', "%$search%") 
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('user_type', 'like', "%$search%");
                });
        });

        return $this;
    }
}
