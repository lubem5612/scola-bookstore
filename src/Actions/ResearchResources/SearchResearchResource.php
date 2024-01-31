<?php

namespace Transave\ScolaBookstore\Actions\ResearchResources;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchResearchResource
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('title', 'like', "%$search%")
                ->orWhere('primary_author', 'like', "%$search%")
                ->orWhere('publisher', 'like', "%$search%")
                ->orWhere('contributors', 'like', "%$search%")
                ->orWhere('publication_date', 'like', "%$search%")
                ->orWhere('publication_year', 'like', "%$search%")
                ->orWhere('source', 'like', "%$search%")
                ->orWhere('resource_url', 'like', "%$search%")
                ->orWhere('resource_type', 'like', "%$search%")
                ->orWhere('keywords', 'like', "%$search%")
                 ->orWhere('department', 'like', "%$search%")
                ->orWhere('faculty', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('category', function ($query2) use ($search) {
                    $query2->where('name', 'like', "%$search%");
                })
                ->orWhereHas('publisher', function ($query3) use ($search) {
                    $query3->where('name', 'like', "%$search%");
                });
        });

        return $this;
    }
}