<?php


namespace Transave\ScolaBookstore\Actions\PaymentDetail;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchPaymentDetails
{
    use SearchHelper;

    public function searchTerms()
    {
        $search = $this->searchParam;
        $user = request()->query('user_id');
        $status = request()->query('status');
        $default = request()->query('default');

        if (isset($user)) $this->queryBuilder->where('user_id', $user);
        if (isset($status)) $this->queryBuilder->where('account_status', $status);
        if (isset($default) || $default == '0') $this->queryBuilder->where('is_default', $default);

        $this->queryBuilder->where(function(Builder $builder) use ($search) {
            $builder->where('account_name', 'like', "%$search%")
                ->orWhere('account_number', 'like', "%$search%")
                ->orWhere('bank_name', 'like', "%$search%")
                ->orWhere('bank_code', 'like', "%$search%");
        });
    }
}