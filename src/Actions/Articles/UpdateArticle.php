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



    private function updateArticle()
    {
        $this->article->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->article->refresh(), 'Article updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'article_id' => 'sometimes|required|exists:articles,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'primary_author' => 'sometimes|required|string|max:255',
            'other_authors' => 'sometimes|required|json',
            'publish_date' => 'sometimes|required|date',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'keywords' => 'sometimes|required|string|max:255|json',
            'references' => 'sometimes|required|string|json',
            'abstract' => 'sometimes|required|string|max:255',
            'introduction' => 'sometimes|required|string|max:255',
            'literature_review' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'discussion' => 'sometimes|required|string|max:255',
            'conclusion' => 'sometimes|required|string|max:255',
            'percentage_share' => 'sometimes|required',
            'methodology' => 'sometimes|required|max:255|string',
            'result' => 'sometimes|required|string|max:255',
            'pages' => 'sometimes|required|string|max:255',
            'ISSN' => 'sometimes|required|string|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file']);
        return $this;

    }
}
