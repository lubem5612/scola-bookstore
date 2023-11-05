<?php

namespace Transave\ScolaBookstore\Actions\Search;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchOrderDetails
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('quantity', 'like', "%$search%" )
                ->orWhere('total_price', 'like', "%$search%")
                ->orWhere('discount', 'like', "%$search%")
                ->orWhereHas('order', function ($query1) use ($search) {
                    $query1 ->where('status', 'like', "%$search%")
                        ->orWhere('invoice_no', 'like', "%$search%");
                })
                ->orWhereHas('book', function ($query2) use ($search) {
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