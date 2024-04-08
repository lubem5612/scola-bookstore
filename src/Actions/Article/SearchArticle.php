<?php


namespace Transave\ScolaBookstore\Actions\Article;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchArticle
{
    use SearchHelper;
    private function searchTerms()
    {
        $search = $this->searchParam;

        $author = request()->query('author_id');
        if (isset($author)) {
            $this->queryBuilder->where('author_id', $author);
        }

        $isPublished = request()->query('is_published');
        if (isset($isPublished) || $isPublished == '0' || $isPublished == '1') {
            $this->queryBuilder->where('is_published', $isPublished);
        }

        $status = request()->query('status');
        if (isset($status) || $status == '0' || $status == '1') {
            $this->queryBuilder->where('status', $status);
        }

        $category = request()->query('category_id');
        if (isset($category)) {
            $this->queryBuilder->whereHas('categories', function (Builder $builder) use ($category) {
                $builder->where('categories.id', $category);
            });
        }

        $this->queryBuilder->where(function ($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('subtitle', 'like', "%$search%")
                ->orWhere('preface', 'like', "%$search%")
                ->orWhere('source', 'like', "%$search%")
                ->orWhere('ISBN', 'like', "%$search%")
                ->orWhere('edition', 'like', "%$search%")
                ->orWhere('summary', 'like', "%$search%")
                ->orWhere('overview', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%")
                ->orWhere('percentage_share', 'like', "%$search%")
                ->orWhereHas('author', function($query2) use ($search) {
                    $query2->whereHas('user', function ($query3) use ($search) {
                        $query3->where('first_name', 'like', "%$search%")
                            ->orWhere('last_name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%");
                    });
                });
        });

        return $this;
    }

    private function querySingleResource()
    {
        if (!is_null($this->id) || isset($this->id)) {
            $this->output = $this->queryBuilder->find($this->id);
            $this->output->update([
                'number_of_views' => (int)$this->output->number_of_views + 1
            ]);
        }
        return $this;
    }
}