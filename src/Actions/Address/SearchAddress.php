<?php


namespace Transave\ScolaBookstore\Actions\Address;


use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchAddress
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $user = request()->query('user_id');
        $default = request()->query('is_default');

        if (isset($user)) $this->queryBuilder->where('user_id', $user);
        if (isset($default)){
            if ($default == 'yes') $this->queryBuilder->where('is_default', 'yes');
            elseif ($default == 'no') $this->queryBuilder->where('is_default', 'no');
        };

        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('address', 'like', "%$search%");
        });

        return $this;
    }
}