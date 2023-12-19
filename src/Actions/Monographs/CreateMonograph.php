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
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');

            $response = $this->uploader->uploadFile($file, 'monographs', 'local');

            if ($response['success']) {
                $this->validatedInput['cover'] = $response['upload_url'];
            }
        }
        return $this;
    }

    
    private function uploadFile(): self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $response = $this->uploader->uploadFile($file, 'monographs', 'local');

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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'abstract'=> 'nullable|string|max:225',
            'primary_author' => 'required|string|max:255',
            'other_authors' => 'nullable|max:255|json',
            'publish_date' => 'required|date',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publisher' => 'nullable|string|max:225',
            'keywords' => 'required|max:255|json',
            'references' => 'required|max:255|json',
            'file' => 'file|max:10000|mimes:png,jpeg,jpg,gif,webp',
            'cover' => 'file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'conclusion' => 'nullable|string|max:225',
            'price' => 'required|integer',
            'percentage_share' => 'nullable',
            'ISBN' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'acknowledgments' => 'nullable|string|max:255',
            'table_of_contents' => 'nullable|string|max:255',
            'license_info' => 'nullable|string|max:255',

        ]);

        $this->validatedInput = Arr::except($data, ['file', 'cover']);
        return $this;

    }
}

 