<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchConferencePaper
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('title', 'like', "%$search%")
                ->orWhere('subtitle', 'like', "%$search%")
                ->orWhere('primary_author', 'like', "%$search%")
                ->orWhere('other_authors', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%")
                ->orWhere('conference_date', 'like', "%$search%")
                ->orWhere('conference_title', 'like', "%$search%")
                ->orWhere('keywords', 'like', "%$search%")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('category', function ($query2) use ($search) {
                    $query2->where('name', 'like', "%$search%");
                });
        });

        return $this;
    }
}