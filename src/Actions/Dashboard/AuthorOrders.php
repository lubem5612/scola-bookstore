<?php


namespace Transave\ScolaBookstore\Actions\Dashboard;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\SearchHelper;

class AuthorOrders
{
    use SearchHelper;

    public function searchTerms()
    {
        $author = request()->query('author_id');
        $paymentStatus = request()->query('payment_status');
        $orderStatus = request()->query('order_status');

        if (isset($author)){
            $this->queryBuilder->whereHas('resource.author', function (Builder $builder) use ($author) {
                $builder->where('id', $author);
            });
        }

        if (isset($orderStatus)) {
            $this->queryBuilder->whereHas('order', function (Builder $builder2) use ($orderStatus) {
                $builder2->where('order_status', $orderStatus);
            });
        }

        if (isset($paymentStatus)) {
            $this->queryBuilder->whereHas('order', function (Builder $builder2) use ($paymentStatus) {
                $builder2->where('payment_status', $paymentStatus);
            });
        }
    }
}