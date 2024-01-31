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
                ->updateAbstractIfExists()
                ->updateContentIfExists()
                ->uploadFileIfExists()
                ->uploadCoverIfExists()
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


    private function updateAbstractIfExists()
    {
        if (isset($this->request['abstract'])) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }
        return $this;
    }



    private function updateContentIfExists()
    {
        if (isset($this->request['content'])) {
            $this->validatedInput['content'] = $this->request['content'];
        }
        return $this;
    }


    private function uploadCoverIfExists(): self
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


        private function uploadFileIfExists(): self
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
            'category_id' => 'sometimes|required|exists:categories,id',
            'publisher_id' => 'sometimes|required|exists:publishers,id',
            'publisher' => 'sometimes|required|string|max:225',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract'=> 'sometimes|required|string|max:225',
            'content'=> 'sometimes|required|string|max:225',
            'primary_author' => 'sometimes|required|string|max:255',
            'contributors' => 'json|max:255|sometimes|required',
            'publication_date' => 'sometimes|required|string|max:255',
            'publication_year' => 'sometimes|required|string|max:255',
            'keywords' => 'sometimes|required|max:255|json',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,docx,wps,webp',
            'cover_image' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required|max:255',
            'ISBN' => 'sometimes|required|string|max:255',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'edition' => 'sometimes|required|string|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'content', 'abstract']);
        return $this;

    }
}
