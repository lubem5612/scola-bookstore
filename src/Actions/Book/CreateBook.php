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

    private function uploadFile(): self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $response = $this->uploader->uploadFile($file, 'books', 'local');

            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function uploadCover(): self
    {
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');

            $response = $this->uploader->uploadFile($file, 'books', 'local');

            if ($response['success']) {
                $this->validatedInput['cover'] = $response['upload_url'];
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
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract' => 'required|string|max:255',
            'primary_author' => 'required|string|max:255',
            "cover" => 'file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'file' => 'required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'publish_date' => 'required|date|max:255',
            'publisher' => 'nullable|string|max:255',
            'other_authors' => 'string|max:255|json',
            'edition' => 'nullable|string|max:255',
            'introduction' => 'nullable|string|max:255',
            'ISBN' => 'nullable|string|max:255|unique:books,ISBN',
            'price' => 'required|integer',
            'tags' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'percentage_share' => 'nullable',
            'language' => 'nullable|string|max:255',
            'table_of_contents' => 'nullable|string|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['cover', 'file']);
        return $this;

    }
}

