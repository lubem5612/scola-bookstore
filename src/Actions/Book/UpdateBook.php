<?php

namespace Transave\ScolaBookstore\Actions\Book;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Book;

class UpdateBook
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $book;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setBookId()
                ->updateAbstract()
                ->updateContent()
                ->uploadCoverIfExists()
                ->uploadFileIfExists()
                ->updateBook();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setBookId()
    {
        $this->book = Book::query()->find($this->validatedInput['book_id']);
        return $this;
    }

    private function uploadCoverIfExists()
    {
        if (isset($this->request['cover_image']) && $this->request['cover_image']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['cover_image'], 'scola-bookstore/books', $this->book, 'cover_image');
            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadFileIfExists()
    {
        if (isset($this->request['file_path']) && $this->request['file_path']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file_path'], 'scola-bookstore/books', $this->book, 'file_path');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
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

    

    private function updateBook()
    {
        $this->book->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->book->refresh(), 'Book updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|max:255|exists:users,id',
            'category_id' => 'sometimes|required|max:255|exists:categories,id',
            'publisher_id' => 'sometimes|required|string|max:255|exists:publishers,id',
            'publisher' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'preface' => 'sometimes|required|string|max:255',
            'abstract' => 'string|max:255',
            'content' => 'string|max:255', //the material
            'primary_author' => 'sometimes|required|string|max:255',
            'contributors' => 'sometimes|required|json|max:255',
            'ISBN' => 'sometimes|required|string|max:255|unique:books,ISBN',
            "cover_image" => 'sometimes|required|file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'publication_date' => 'string|max:255',
            'edition' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'tags' => 'sometimes|required|string|max:255',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'summary' => 'sometimes|required|string|max:255',
            'percentage_share' => 'sometimes|required',
        ]);

        $this->validatedInput = Arr::except($data, ['cover_image', 'file_path', 'content', 'abstract']);
        return $this;

    }
}
