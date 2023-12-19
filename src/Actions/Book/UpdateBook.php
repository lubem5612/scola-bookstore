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
        if (isset($this->request['cover']) && $this->request['cover']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['cover'], 'scola-bookstore/Books', $this->book, 'cover');
            if ($response['success']) {
                $this->validatedInput['cover'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Books', $this->book, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
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
            'publisher_id' => 'sometimes|required|max:255|exists:publishers,id',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|string|max:255',
            'primary_author' => 'sometimes|required|string|max:255',
            'other_authors' => 'sometimes|json',
            "cover" => 'sometimes|file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'publish_date' => 'sometimes|required|date|max:255',
            'publisher' => 'sometimes|required|string|max:255|',
            'edition' => 'sometimes|string|max:255',
            'ISBN' => 'sometimes|string|max:255',
            'price' => 'sometimes|required|integer',
            'tags' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string|max:255',
            'percentage_share' => 'sometimes|required',
            'introduction' => 'sometimes|string|max:255',
            'language' => 'sometimes|string|max:255',
            'table_of_contents' => 'sometimes|string|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['cover', 'file']);
        return $this;

    }
}
