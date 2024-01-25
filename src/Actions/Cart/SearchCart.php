<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchCart
{
    use SearchHelper;
    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search){
            $query
                ->where('resource_type', 'like', "%$search")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('role', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
        });
        return $this;
    }
}