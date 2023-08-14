<?php

namespace Transave\ScolaBookstore\Actions\Book;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Book;

class DeleteBook
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Book $book;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setBook()
                ->deleteCover()
                ->deleteFile()
                ->deleteBook();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteBook()
    {
        $this->book->delete();
        return $this->sendSuccess(null, 'book deleted successfully');
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
    private function deleteCover() : self
    {
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function setBook() :self
    {
        $this->book = Book::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:books,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}