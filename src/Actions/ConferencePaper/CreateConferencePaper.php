<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\User;
use Illuminate\Support\Facades\Config;



class CreateConferencePaper
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
                ->setUser()
                ->createContent()
                ->createAbstract()
                ->uploadFile()
                ->uploadCover()
                ->setPercentageShare()
                ->createPaper();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function uploadFile():self
    {
        if (array_key_exists('file_path', $this->request)) {
            $response = $this->uploader->uploadFile($this->request['file_path'], 'papers');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadCover():self
    {
        if (array_key_exists('cover_image', $this->request)) {
            $response = $this->uploader->uploadFile($this->request['cover_image'], 'papers');
            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }


    private function createContent(): self
    {
        if (array_key_exists('content', $this->request)) {
            $this->validatedInput['content'] = $this->request['content'];
        }

        return $this;
    }



    private function createAbstract(): self
    {
        if (array_key_exists('abstract', $this->request)) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }

        return $this;
    }


    private function createPaper()
    {
        $conferencePaper = ConferencePaper::query()->create($this->validatedInput);
        return $this->sendSuccess($conferencePaper->load('user', 'category'), 'Paper created successfully');
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
            'conference_name'=> 'required|string|max:255',
            'conference_date'=> 'required|string|max:255',
            'conference_year'=> 'required|string|max:255',
            'conference_location'=> 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'abstract'=> 'string|max:225',
            'content'=> 'string|max:225',
            'primary_author' => 'required|string|max:255',
            'contributors' => 'json|max:255',
            'keywords' => 'json|max:255',
            'institutional_affiliations' => 'json|max:255',
            'file_path' => 'file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'price' => 'required|integer',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'percentage_share' => 'nullable|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'abstract', 'content']);
        return $this;

    }
}

 