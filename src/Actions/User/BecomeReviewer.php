<?php

namespace Transave\ScolaBookstore\Actions\User;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;


class BecomeReviewer
{
    use ValidationHelper, ResponseHelper;

    private $request, $user;
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
                ->updateUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser()
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }


    private function updateUser()
    {
        $this->user->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->user->refresh(), 'Congrats, you are now a reviewer', 200);
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'user_type' => 'required|string|max:255|in:reviewer, normal',  
        ]);
        return $this;
    }
}
