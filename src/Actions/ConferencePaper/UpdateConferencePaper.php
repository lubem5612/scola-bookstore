<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;

class UpdateConferencePaper
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $conferencePaper;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setPaperId()
                ->updateAbstractIfExists()
                ->updateContentIfExists()
                ->uploadFileIfExists()
                ->uploadCoverIfExists()
                ->updatePaper();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setPaperId()
    {
        $this->conferencePaper = ConferencePaper::query()->find($this->validatedInput['paper_id']);
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
            $response = $this->uploader->uploadOrReplaceFile($this->request['file_path'], 'scola-bookstore/papers', $this->conferencePaper, 'file_path');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadCoverIfExists()
    {
        if (isset($this->request['cover_image']) && $this->request['cover_image']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['cover_image'], 'scola-bookstore/papers', $this->conferencePaper, 'cover_image');
            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updatePaper()
    {
        $this->conferencePaper->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->conferencePaper->refresh(), 'Conference Paper updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'paper_id' => 'required|exists:conference_papers,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'conference_name'=> 'sometimes|required|string|max:255',
            'conference_date'=> 'sometimes|required|string|max:255',
            'conference_year'=> 'sometimes|required|string|max:255',
            'conference_location'=> 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract'=> 'sometimes|required|string|max:225',
            'content'=> 'sometimes|required|string|max:225',
            'primary_author' => 'sometimes|required|string|max:255',
            'contributors' => 'json|max:255',
            'keywords' => 'sometimes|required|json|max:255',
            'institutional_affiliations' => 'sometimes|required|json|max:255',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'content', 'abstract']);
        return $this;

    }
}
