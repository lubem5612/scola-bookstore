<?php

namespace Transave\ScolaBookstore\Actions\Monographs;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Monograph;

class UpdateMonograph
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
                ->setMonographId()
                ->uploadFileIfExists()
                ->updateMonograph();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setMonographId()
    {
        $this->monograph = Monograph::query()->find($this->validatedInput['monograph_id']);
        return $this;
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



    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Monographs', $this->monograph, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updateMonograph()
    {
        $this->monograph->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->monograph->refresh(), 'Monograph updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'monograph_id' => 'required|exists:monographs,id',
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
