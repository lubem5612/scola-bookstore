<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Article;

class UpdateArticle
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $article;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setArticleId()
                ->updateAbstract()
                ->updateContent()
                ->uploadFileIfExists()
                ->updateArticle();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setArticleId()
    {
        $this->article = Article::query()->find($this->validatedInput['article_id']);
        return $this;
    }


    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Articles', $this->article, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updateAbstract()
    {
        if (isset($this->request['abstract'])) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }
        return $this;
    }



    private function updateContent()
    {
        if (isset($this->request['content'])) {
            $this->validatedInput['content'] = $this->request['content'];
        }
        return $this;
    }



    private function updateArticle()
    {
        $this->article->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->article->refresh(), 'Article updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'article_id' => 'required|exists:articles,id',
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'sometimes|required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publishers,id|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract'=> 'string|max:225',
            'content'=> 'string|max:225',
            'primary_author' => 'sometimes|required|max:255',
            'publication_date' => 'sometimes|required|string',
            'contributors' => 'json|max:255',
            'keywords' => 'json|max:255',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'price' => 'integer|max:255',
            'pages' => 'string|max:255',
            'percentage_share' => 'sometimes|required|max:255',
            
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'abstract', 'content']);
        return $this;

    }
}
