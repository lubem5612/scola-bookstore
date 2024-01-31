<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\User;
use Illuminate\Support\Facades\Config;


class CreateArticle
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    

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
                ->createContent()
                ->createAbstract()
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
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }


    private function createArticle()
    {
        $article = Article::query()->create($this->validatedInput);
        return $this->sendSuccess($article->load('user', 'category', 'publisher'), 'Article created successfully');
    }


    private function createContent(): self
    {
        if (array_key_exists('content', $this->request)) {
            $this->validatedInput['content'] = $this->request['content'];
        }

        return $this;
    }



    private function createAbstract(): self
    {
        if (array_key_exists('abstract', $this->request)) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }

        return $this;
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
            'publisher_id' => 'required|exists:publishers,id|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract'=> 'string|max:225',
            'content'=> 'string|max:225',
            'primary_author' => 'required|string|max:255',
            'publication_date' => 'required|string',
            'contributors' => 'json|max:255',
            'keywords' => 'json|max:255',
            'file_path' => 'file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'price' => 'integer|max:255',
            'pages' => 'string|max:255',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'percentage_share' => 'nullable|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'abstract', 'content']);
        return $this;

    }
    
}

 