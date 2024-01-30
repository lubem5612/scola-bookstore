<?php

namespace Transave\ScolaBookstore\Actions\Reviewer;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ReviewerRequest;

class DeleteReviewer
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->findReviewer()
                ->deleteReviewerRequest();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function findReviewer(): self
    {
        $this->reviewer = ReviewerRequest::find($this->validatedInput['reviewer_id']);

        if (!$this->reviewer) {
            $this->sendError('Reviewer  not found', [], 404);
        }

        return $this;
    }

    private function deleteReviewerRequest()
    {
        $this->reviewer->delete();

        return $this->sendSuccess([], 'Reviewer deletRed successfully', 200);
    }


    
    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'reviewer_id' => 'required|exists:reviewer_requests,id',
        ]);
        return $this;
    }
}
