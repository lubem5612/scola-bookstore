<?php

namespace Transave\ScolaBookstore\Actions\Journals;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Journal;

class UpdateJournal
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
                ->setJournalId()
                ->uploadCoverIfExists()
                ->uploadFileIfExists()
                ->updateJournal();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setJournalId()
    {
        $this->journal = Journal::query()->find($this->validatedInput['journal_id']);
        return $this;
    }



    private function uploadCoverIfExists()
    {
        if (isset($this->request['cover']) && $this->request['cover']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['cover'], 'scola-bookstore/Journals', $this->journal, 'cover');
            if ($response['success']) {
                $this->validatedInput['cover'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Journals', $this->journal, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updateJournal()
    {
        $this->journal->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->journal->refresh(), 'Journal updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'journal_id' => 'required|exists:journals,id|max:255',
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publisers,id|max:255',
            'publish_date' => 'sometimes|required|date',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'ISSN' => 'sometimes|required|string|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'editors'=> 'sometimes|required|json|max:255',
            'website'=> 'sometimes|required|string|max:255',
            'table_of_contents'=> 'nullable|string|max:255',
            'editorial'=> 'sometimes|required|string|max:255',
            'editorial_board_members'=> 'sometimes|required|json|maax:255',
            'articles'=> 'sometimes|required|string|max:255',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'conclusion' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required',
        ]);

        $this->validatedInput = Arr::except($data, ['file', 'cover']);
        return $this;

    }
    
}

 