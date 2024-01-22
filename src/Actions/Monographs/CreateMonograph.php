<?php

namespace Transave\ScolaBookstore\Actions\Monographs;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Http\Models\User;


class CreateMonograph
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput; 
    private $user;
    private $uploader;
    private $monograph;

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
                ->createMonograph();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function uploadCover(): self
    {
        if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');

            $response = $this->uploader->uploadFile($file, 'monographs', 'local');

            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }

    
    private function uploadFile(): self
    {
        if (request()->hasFile('file_path')) {
            $file = request()->file('file_path');

            $response = $this->uploader->uploadFile($file, 'monographs', 'local');

            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }


    private function setUser(): self
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }

    private function createMonograph()
    {
        $monograph = Monograph::query()->create($this->validatedInput);
        return $this->sendSuccess($monograph->load('user', 'category', 'publisher'), 'Monograph created successfully');
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
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publisher' => 'nullable|string|max:225',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract'=> 'string|max:225',
            'primary_author' => 'required|string|max:255',
            'contributors' => 'json|max:255',
            'publication_date' => 'string|max:255',
            'publication_year' => 'required|string|max:255',
            'keywords' => 'required|max:255|json',
            'file_path' => 'nullable|file|max:10000|mimes:pdf,doc,docx,wps,webp',
            'cover_image' => 'nullable|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'price' => 'required|integer',
            'percentage_share' => 'required|max:255',
            'ISBN' => 'string|max:255',
            'edition' => 'string|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image']);
        return $this;

    }
}

 