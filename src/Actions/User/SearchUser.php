<?php

namespace Transave\ScolaBookstore\Actions\User;

use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchUser
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function (Builder $query) use ($search) {
            $query
                ->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhereHas('author', function (Builder $builder) use ($search) {
                    $builder->where('specialization', 'like', "%$search%")
                        ->orWhereHas('department', function (Builder $department) use ($search) {
                            $department->where('name', 'like', "%$search%");
                        })->orWhereHas('faculty', function (Builder $faculty) use ($search) {
                            $faculty->where('name', 'like', "%$search%");
                        });
                })->orWhereHas('reviewer', function (Builder $reviewer) use ($search) {
                    $reviewer->where('specialization', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%");
                });
        });

        return $this;
    }
}