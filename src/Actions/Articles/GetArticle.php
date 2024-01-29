<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Transave\ScolaBookstore\Events\ArticleViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Article;

class GetArticle
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Article $article;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setArticle()
                ->sendSuccess($this->article, 'Article fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setArticle()
    {
        $this->article = Article::query()->with(['user', 'category', 'publisher'])->find($this->request['id']);
        return $this;
    }


    

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:articles,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}