<?php

namespace Transave\ScolaBookstore\Actions\Book;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchBook
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('title', 'like', "%$search%")
                ->orWhere('subtitle', 'like', "%$search%")
                ->orWhere('primary_author', 'like', "%$search%")
                ->orWhere('other_authors', 'like', "%$search%")
                ->orWhere('edition', 'like', "%$search%")
                ->orWhere('publish_date', 'like', "%$search%")
                ->orWhere('publisher', 'like', "%$search%")//during registration, user's enter new publisher if the publisher been searched is not registered/found.
                ->orWhere('tags', 'like', "%$search%")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('category', function ($query2) use ($search) {
                    $query2->where('name', 'like', "%$search%");
                })
                ->orWhereHas('publisher', function ($query3) use ($search) {
                    $query3->where('name', 'like', "%$search%");
                });
        });

        return $this;
    }
}