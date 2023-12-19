<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchArticle
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
                ->orWhere('published_date', 'like', "%$search%")
                ->orWhere('keywords', 'like', "%$search%")
                ->orWhere('ISSN', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%")
                ->orWhereHas('user', function ($query1) use ($search) {
                    $query1->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('category', function ($query2) use ($search) {
                    $query2->where('name', 'like', "%$search%");
                });
        });

        return $this;
    }
}