<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Transave\ScolaBookstore\Helpers\SearchHelper;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class SearchCart
{
    use SearchHelper;

    private function searchTerms()
    {

        $search = $this->searchParam;
        $this->queryBuilder->where(function($query) use($search) {
            $query->where('resource_type', "like", "%$search%")
                   ->orWhere('resource_id', "like", "%$search%")
                ->orWhereHas('user', function ($query2) use ($search) {
                    $query2->where('first_name', 'like', "%$search%")
                           ->orWhere('last_name', 'like', "%$search%")
                           ->orWhere('role', 'like', "%$search%");
                });
        });
        return $this;
    }
}
