<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\User;


class CreateConferencePaper
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
                ->setUser()
                ->uploadFile()
                ->setPercentageShare()
                ->createPaper();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function uploadFile(): self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $response = $this->uploader->uploadFile($file, 'papers', 'local');

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
            'conference_title'=> 'required|string|max:255',
            'conference_date'=> 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'abstract'=> 'required|string|max:225',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'primary_author' => 'required|string|max:255',
            'other_authors' => 'string|max:255|json',
            'keywords' => 'nullable|string|max:255|json',
            'references' => 'nullable|max:255|string|json',
            'file' => 'required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'introduction' => 'nullable|string|max:255',
            'background' => 'nullable|string|max:255',
            'methodology' => 'nullable|string|max:255',
            'conclusion' => 'nullable|string|max:225',
            'result' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'pages' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'percentage_share' => 'nullable',
        ]);


        $this->validatedInput = Arr::except($data, ['file']);
        return $this;

    }
}

 