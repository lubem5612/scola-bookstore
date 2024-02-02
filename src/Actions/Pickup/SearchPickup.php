<?php

namespace Transave\ScolaBookstore\Actions\Pickup;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchPickup
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('address', 'like', "%$search%")
                ->orWhere('postal_code', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('alternative_phone', 'like', "%$search%")
                ->orWhere('recipient_first_name', 'like', "%$search%")
                ->orWhere('recipient_last_name', 'like', "%$search%")
                ->orWhereHas('state', function ($queryState) use ($search) {
                    $queryState->where('name', 'like', "%$search%")
                          ->orWhere('capital', 'like', "%$search%")
                              ->orWhereHas('country', function ($querycountry) use ($search) { 
                                  $querycountry->where('name', 'like', "%$search%")
                                               ->orWhere('code', 'like', "%$search%");
                                               });
                })
               ->orWhereHas('lg', function ($queryLg) use ($search) {
                    $queryLg->where('name', 'like', "%$search%")
                           ->orWhereHas('state', function ($queryState) use ($search) {
                               $queryState->where('name', 'like', "%$search%") 
                                        ->orWhere('capital', 'like', "%$search%")
                                          ->orWhereHas('country', function ($querycountry) use ($search) { 
                                             $querycountry->where('name', 'like', "%$search%")
                                               ->orWhere('code', 'like', "%$search%");
                                               }); 
                               });
               })
               ->orWhereHas('country', function ($querycountry) use ($search) { 
                        $querycountry->where('name', 'like', "%$search%")
                                     ->orWhere('code', 'like', "%$search%");
                 })
                ->orWhereHas('order', function ($queryorder) use ($search) {
                    $queryorder
                        ->where('invoice_number', 'like', "%$search%")
                        ->orWhere('order_date', 'like', "%$search%")
                        ->orWhere('delivery_status', 'like', "%$search%")
                        ->orWhere('payment_status', 'like', "%$search%")
                        ->orWhere('payment_reference', 'like', "%$search%")
                          ->orWhereHas('user', function ($queryuser) use ($search) {
                            $queryuser
                                ->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('phone', 'like', "%$search%");
                          }); 
                });
        });

        return $this;
    }
}