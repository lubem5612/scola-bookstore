<?php

namespace Transave\ScolaBookstore\Actions\Journals;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Http\Models\User;


class CreateJournal
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $journal;

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
                ->uploadCover()
                ->setPercentageShare()
                ->createJournal();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function uploadCover(): self
    {
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');

            $response = $this->uploader->uploadFile($file, 'journals', 'local');

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

            $response = $this->uploader->uploadFile($file, 'journals', 'local');

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


    private function createJournal()
    {
        $journal = Journal::query()->create($this->validatedInput);
        return $this->sendSuccess($journal->load('user', 'category', 'publisher'), 'Journal created successfully');
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
            'publisher_id' => 'nullable|exists:publisers,id|max:255',
            'publish_date' => 'nullable|date',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'ISSN' => 'nullable|string|max:255',
            'publisher'=> 'nullable|string|max:255',
            'editors'=> 'nullable|json|max:255',
            'website'=> 'nullable|string|max:255',
            'table_of_contents'=> 'nullable|string|max:255',
            'editorial'=> 'nullable|string|max:255',
            'editorial_board_members'=> 'nullable|json|maax:255',
            'articles'=> 'nullable|string|max:255',
            'file' => 'required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover' => 'nullable|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'conclusion' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'percentage_share' => 'nullable',
        ]);

        $this->validatedInput = Arr::except($data, ['file', 'cover']);
        return $this;

    }
    
}

 