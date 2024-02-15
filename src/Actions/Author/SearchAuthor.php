<?php


namespace Transave\ScolaBookstore\Actions\Author;


use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchAuthor
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $user = request()->query('user_id');
        if (isset($user)) $this->queryBuilder->where('user_id', $user);

        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('specialization', 'like', "%$search%")
                ->orWhereHas('user', function ($query2) use ($search) {
                    $query2->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                })
                ->orWhereHas('faculty', function ($query3) use ($search) {
                    $query3->where('name', 'like', "%$search%");
                })
                ->orWhereHas('department', function ($query4) use ($search) {
                    $query4->where('name', 'like', "%$search%");
                });
        });

        return $this;
    }
}