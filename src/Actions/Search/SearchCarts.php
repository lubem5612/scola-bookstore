<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchCarts
{
    use SearchHelper;
    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search){
            $query
                ->where('amount', 'like', "%$search")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('book', function ($query2) use ($search){
                    $query2->where('title', 'like', "%$search%")
                        ->orWhere('subtitle', 'like', "%$search%")
                        ->orWhere('author', 'like', "%$search%")
                        ->orWhere('edition', 'like', "%$search%")
                        ->orWhere('publish_date', 'like', "%$search%")
                        ->orWhere('publisher', 'like', "%$search%")
                        ->orWhere('tags', 'like', "%$search%");
                });
        });
        return $this;
    }
}