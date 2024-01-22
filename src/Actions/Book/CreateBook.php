<?php

namespace Transave\ScolaBookstore\Actions\Book;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Book;


class CreateBook
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
                ->setUser()
                ->uploadCover()
                ->uploadFile()
                ->setPercentageShare()
                ->createBook();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function uploadFile():self
    {
        if (array_key_exists('file_path', $this->request)) {
            $response = $this->uploader->uploadFile($this->request['file_path'], 'books');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }


    private function uploadCover():self
    {
        if (array_key_exists('cover_image', $this->request)) {
            $response = $this->uploader->uploadFile($this->request['cover_image'], 'books');
            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function setUser(): self
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function createBook()
    {
        $book = Book::query()->create($this->validatedInput);
        return $this->sendSuccess($book->load('user', 'category', 'publisher'), 'Book created successfully');
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
            'user_id' => 'required|max:255|exists:users,id',
            'category_id' => 'required|max:255|exists:categories,id',
            'publisher_id' => 'string|max:255|exists:publishers,id',
            'publisher' => 'string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'preface' => 'string|max:255',
            'primary_author' => 'required|string|max:255',
            'contributors' => 'json|max:255',
            'ISBN' => 'string|max:255|unique:books,ISBN',
            "cover_image" => 'image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'file_path' => 'file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'publication_date' => 'string|max:255',
            'edition' => 'string|max:255',
            'price' => 'required|integer',
            'tags' => 'string|max:255',
            'summary' => 'string|max:255',
            'percentage_share' => 'nullable',
        ]);

        $this->validatedInput = Arr::except($data, ['cover_image', 'file_path']);
        return $this;

    }
}

