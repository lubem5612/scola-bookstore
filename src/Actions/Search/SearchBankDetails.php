<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchBankDetails
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('account_number', 'like', "%$search%")
                ->orWhere('account_name', 'like', "%$search%")
                ->orWhereHas('bank', function ($query1) use ($search) {
                    $query1->where('name', 'like', "%$search%")
                           ->orWhere('code', 'like', "%$search%")
                           ->orWhere('country', 'like', "%$search%");
                 })
                  ->orWhereHas('user', function ($query11) use ($search) {
                     $query11->where('first_name', 'like', "%$search%")
                              ->orWhere('last_name', 'like', "%$search%")
                              ->orWhere('email', 'like', "%$search%")
                              ->orWhere('phone', 'like', "%$search%");
                   }); 
                });

        return $this;
    }
}