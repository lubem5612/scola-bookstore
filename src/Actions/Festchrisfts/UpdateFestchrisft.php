<?php

namespace Transave\ScolaBookstore\Actions\Festchrisfts;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Festchrisft;

class UpdateFestchrisft
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
                ->setFestchrisftId()
                ->updateAbstractIfExists()
                ->updateContentIfExists()
                ->uploadFileIfExists()
                ->uploadCoverIfExists()
                ->updateFestchrisft();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setFestchrisftId()
    {
        $this->festchrisft = Festchrisft::query()->find($this->validatedInput['festchrisft_id']);
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
    


    private function uploadFileIfExists()
    {
        if (isset($this->request['file_path']) && $this->request['file_path']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file_path'], 'scola-bookstore/festchrisfts', $this->festchrisft, 'file_path');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }


        private function uploadCoverIfExists()
    {
        if (isset($this->request['cover_image']) && $this->request['cover_image']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['cover_image'], 'scola-bookstore/festchrisfts', $this->festchrisft, 'cover_image');
            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updateFestchrisft()
    {
        $this->festchrisft->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->festchrisft->refresh(), 'Festchrisft updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'festchrisft_id' => 'required|exists:festchrisfts,id',
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'sometimes|required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publishers,id|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:255',
            'publication_date' => 'sometimes|required|string|max:255',
            'editors'=> 'sometimes|required|json|max:255',
            'keywords'=> 'sometimes|required|json|max:255', 
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp', 
            'dedicatees'=> 'sometimes|required|json|max:255', 
            'introduction'=> 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'percentage_share' => 'sometimes|required|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'content', 'abstract']);
        return $this;

    }
}
