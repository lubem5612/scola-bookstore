<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\User;


class CreateArticle
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
                ->setUser()
                ->uploadFile()
                ->setPercentageShare()
                ->createArticle();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function uploadFile(): self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $response = $this->uploader->uploadFile($file, 'articles', 'local');

            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function setUser(): self
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }


    private function createArticle()
    {
        $article = Article::query()->create($this->validatedInput);
        return $this->sendSuccess($article->load('user', 'category'), 'Article created successfully');
    }


    private function setPercentageShare(): self
    {
        if (!array_key_exists('percentage_share', $this->request)) {
            $this->request['percentage_share'] = config('scola-bookstore.percentage_share');
        }
        return $this;
    }

    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'required|exists:categories,id|max:255',
            'discussion'=> 'nullable|string|max:225',
            'literature_review'=> 'nullable|string|max:225',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract'=> 'required|string|max:225',
            'primary_author' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'other_authors' => 'nullable|string|max:255|json',
            'keywords' => 'required|string|max:255|json',
            'references' => 'required|string|max:255|json',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'introduction' => 'nullable|string|max:255',
            'methodology' => 'nullable|string|max:255',
            'conclusion' => 'required|string|max:225',
            'result' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'pages' => 'nullable|string|max:255',
            'percentage_share' => 'nullable',
            'ISSN' => 'nullable|string|max:255',  

        ]);

        $this->validatedInput = Arr::except($data, ['file']);
        return $this;

    }
    
}

 