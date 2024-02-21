<?php


namespace Transave\ScolaBookstore\Actions\Order;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchOrder
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $user = request()->query('user_id');
        if (isset($user)) {
            $this->queryBuilder->where('user_id', $user);
        }

        $this->queryBuilder->where(function (Builder $builder) use ($search) {
            $builder->where('invoice_number', 'like', "%$search%")
                ->orWhere('delivery_status', 'like', "%$search%")
                ->orWhere('order_status', 'like', "%$search%")
                ->orWhere('payment_status', 'like', "%$search%")
                ->orWhere('payment_reference', 'like', "%$search%")
                ->orWhere('total_amount', 'like', "%$search%")
                ->orWhereHas('user', function (Builder $builder2) use ($search) {
                    $builder2->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
        });

        return $this;
    }
}