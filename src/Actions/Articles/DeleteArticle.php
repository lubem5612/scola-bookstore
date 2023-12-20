<?php

namespace Transave\ScolaBookstore\Actions\Articles;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Article;

class DeleteArticle
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Article $article;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }


    /**
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setArticle()
                ->deleteFile()
                ->deleteArticle();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteArticle()
    {
        $this->article->delete();
        return $this->sendSuccess('Article deleted successfully', [], 200);
    }


    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function setArticle() :self
    {
        $this->article = Article::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:articles,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}