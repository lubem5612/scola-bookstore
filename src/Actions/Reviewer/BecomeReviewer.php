<?php

namespace Transave\ScolaBookstore\Actions\Reviewer;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ReviewerRequest;
use Transave\ScolaBookstore\Http\Models\User;



class BecomeReviewer
{
    use ValidationHelper, ResponseHelper;

    private $request, $user, $reviewerRequest;
    private $validatedInput;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setUser()
                ->checkIfUserExists()
                ->sendRequest();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser(): self
    {
        $this->user = config('scola-bookstore.auth_model')::find($this->request['user_id']);

        if (!$this->user) {
            $this->sendError('User not found', [], 404);
        }

        return $this;
    }


    private function checkIfUserExists(): self
    {
        $this->reviewerRequest = ReviewerRequest::query()->find($this->user->id);
       
        if ($this->reviewerRequest) {
            $this->sendError('You have already submitted a reviewer request', [], 401);
        }
        return $this;
    }



    private function sendRequest()
    {
        $this->reviewerRequest = new ReviewerRequest($this->validatedInput);
        $this->user->reviewerRequest()->save($this->reviewerRequest);

        return $this->sendSuccess($this->user->refresh(), 'Congratulations, you are now a reviewer', 200);
    }


    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'specialization' => 'required|string',
            'previous_projects' => 'required|array',
            'previous_projects.*' => 'required|string',
            'year_of_project' => 'required|integer',
        ]);
        return $this;
    }
}
