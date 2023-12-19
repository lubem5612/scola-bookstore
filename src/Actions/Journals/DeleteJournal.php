<?php

namespace Transave\ScolaBookstore\Actions\Journal;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Journal;

class DeleteJournal
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Journal $journal;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setJournal()
                ->deleteCover()
                ->deleteFile()
                ->deleteJournal();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteJournal()
    {
        $this->journal->delete();
        return $this->sendSuccess(null, 'journal deleted successfully');
    }


    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function deleteCover() : self
    {
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function setJournal() :self
    {
        $this->journal = Journal::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:journals,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}