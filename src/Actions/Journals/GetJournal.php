<?php

namespace Transave\ScolaBookstore\Actions\Journal;

use Transave\ScolaBookstore\Events\JournalViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Journal;


class GetJournal
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Journal $journal;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setJournal()
                ->sendSuccess($this->journal, 'Journal fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function setJournal()
    {
        $this->journal = Journal::query()
            ->with(['user', 'category', 'publisher'])
            ->find($this->request['id']);

        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:journals,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}