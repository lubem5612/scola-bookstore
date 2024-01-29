<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchOrderItems
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('total_amount', 'like', "%$search%")
                ->orWhere('invoice_number', 'like', "%$search%")

                ->orWhereHas('order', function ($query1) use ($search) {
                    $query1->where('invoice_number', 'like', "%$search%")
                        ->orWhere('order_date', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhereHas('user', function ($query11) use ($search) {
                            $query11->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    - n   >orWhere('email', 'like', "%$search%")
                                    ->orWhere('phone', 'like', "%$search%");
                            });
                    }); 
                });

        return $this;
    }
}
