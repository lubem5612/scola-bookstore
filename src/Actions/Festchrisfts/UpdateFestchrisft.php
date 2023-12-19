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
    private $festchrisft;

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
                ->uploadFileIfExists()
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


    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Articles', $this->festchrisft, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
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
            'category_id' => 'required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publisers,id|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'publish_date' => 'sometimes|required|date',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract' => 'nullable|string|max:255',
            'editors'=> 'nullable|json|max:255',
            'keywords'=> 'sometimes|required|json|max:255', 
            'table_of_contents'=> 'sometimes|required|string|max:255',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'references'=> 'sometimes|required|json|max:255',
            'dedicatees'=> 'sometimes|required|json|maax:255',
            'introduction'=> 'sometimes|required|string|max:255',
            'price' => 'required|integer',
            'percentage_share' => 'nullable',
        ]);

        $this->validatedInput = Arr::except($data, ['file', 'cover']);
        return $this;

    }
}
