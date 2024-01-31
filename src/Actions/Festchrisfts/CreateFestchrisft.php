<?php

namespace Transave\ScolaBookstore\Actions\Festchrisfts;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Http\Models\User;
use Illuminate\Support\Facades\Config;


class CreateFestchrisft
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
                ->createFestchrisft();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function uploadCover(): self
    {
        if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');

            $response = $this->uploader->uploadFile($file, 'festchrisfts', 'local');

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

            $response = $this->uploader->uploadFile($file, 'festchrisfts', 'local');

            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
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

    private function createFestchrisft()
    {
        $festchrisft = Festchrisft::query()->create($this->validatedInput);
        return $this->sendSuccess($festchrisft->load('user', 'category', 'publisher'), 'festchrisft created successfully');
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
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'required|exists:categories,id|max:255',
            'publisher_id' => 'nullable|exists:publishers,id|max:255',
            'publisher'=> 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'abstract' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:255',
            'publication_date' => 'nullable|string|max:255',
            'editors'=> 'nullable|json|max:255',
            'keywords'=> 'nullable|json|max:255', 
            'file_path' => 'nullable|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'nullable|image|max:5000|mimes:png,jpeg,jpg,gif,webp', 
            'dedicatees'=> 'nullable|json|max:255', 
            'introduction'=> 'nullable|string|max:255',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'price' => 'required|integer',
            'percentage_share' => 'nullable|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'content', 'abstract']);
        return $this;

    }
}
