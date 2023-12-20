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


    private function uploadFileIfExists()
    {
        if (isset($this->request['file_path']) && $this->request['file_path']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file_path'], 'scola-bookstore/journals', $this->journal, 'file_path');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
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
            'category_id' => 'sometimes|required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publisers,id|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'publication_date' => 'sometimes|required|string|max:255',
            'editors'=> 'sometimes|required|json|max:255',
            'website'=> 'sometimes|required|string|max:255',
            'editorial'=> 'sometimes|required|string|max:255',
            'editorial_board_members'=> 'sometimes|required|json|maax:255',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'conclusion' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path']);
        return $this;

    }
    
}

 