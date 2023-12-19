<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Transave\ScolaBookstore\Events\ConferencePaperViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;

class GetConferencePaper
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private ConferencePaper $conferencePaper;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setPaper()
                ->sendSuccess($this->conferencePaper, 'Conference Paper fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function setPaper()
    {
        $this->conferencePaper = ConferencePaper::query()
            ->with(['user', 'category'])
            ->find($this->request['id']);

        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:conference_papers,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}