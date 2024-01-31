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
                ->orWhere('resource_type', 'like', "%$search%")
                ->orWhere('quantity', 'like', "%$search%")
                ->orWhere('total_amount', 'like', "%$search%")
                ->orWhereHas('order', function ($query1) use ($search) {
                    $query1->where('invoice_number', 'like', "%$search%")
                        ->orWhere('order_date', 'like', "%$search%")
                        ->orWhere('delivery_status', 'like', "%$search%")
                        ->orWhere('payment_status', 'like', "%$search%")
                        ->orWhere('payment_reference', 'like', "%$search%")
                        ->orWhereHas('user', function ($query11) use ($search) {
                            $query11->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%")
                                    ->orWhere('phone', 'like', "%$search%");
                            });
                    }); 
                });

        return $this;
    }
}