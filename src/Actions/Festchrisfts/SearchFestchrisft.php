<?php

namespace Transave\ScolaBookstore\Actions\Festchrisfts;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchFestchrisft
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('publisher', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%")
                ->orWhere('editors', 'like', "%$search%")
                ->orWhere('keywords', 'like', "%$search%")
                ->orWhere('publication_date', 'like', "%$search%")
                ->orWhere('dedicatees', 'like', "%$search%")
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