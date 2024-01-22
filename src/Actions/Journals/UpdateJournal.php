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
                ->updateAbstract()
                ->updateContent()
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


    private function updateAbstract()
    {
        if (isset($this->request['abstract'])) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }
        return $this;
    }



        private function updateContent()
    {
        if (isset($this->request['content'])) {
            $this->validatedInput['content'] = $this->request['content'];
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
            'publisher_id' => 'nullable|exists:publishers,id|max:255',
            'title' => 'sometimes|required|string|max:255',
            'volume' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'subtitle' => 'string|max:255',
            'publisher'=> 'string|max:255',
            'publication_date' => 'string|max:255',
            'publication_year' => 'string|max:255',
            'abstract' => 'string|max:255',
            'content' => 'string|max:255', //the material
            'page_start' => 'string|max:255',
            'page_end' => 'string|max:255',
            'editors'=> 'json|max:255',
            'website'=> 'string|max:255',
            'editorial'=> 'string|max:255',
            'editorial_board_members'=> 'json|max:255',
            'file_path' => 'file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'conclusion' => 'string|max:255',
            'percentage_share' => 'numeric',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'content', 'abstract']);
        return $this;

    }
    
}

 