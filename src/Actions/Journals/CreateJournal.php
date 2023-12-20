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
                ->setPercentageShare()
                ->createJournal();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function uploadFile(): self
    {
        if (request()->hasFile('file_path')) {
            $file = request()->file('file_path');

            $response = $this->uploader->uploadFile($file, 'journals', 'local');

            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
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
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:255',
            'publisher'=> 'string|max:255',
            'publication_date' => 'string|max:255',
            'editors'=> 'json|max:255',
            'website'=> 'string|max:255',
            'editorial'=> 'string|max:255',
            'editorial_board_members'=> 'json|maax:255',
            'file_path' => 'required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'conclusion' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'percentage_share' => 'nullable',
            
        ]);

        $this->validatedInput = Arr::except($data, ['file_path']);
        return $this;

    }
    
}

 