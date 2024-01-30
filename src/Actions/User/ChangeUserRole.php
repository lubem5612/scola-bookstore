<?php

namespace Transave\ScolaBookstore\Actions\User;

use Illuminate\Support\Facades\Config;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\User;

class ChangeUserRole
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private User $user;

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
                ->updateRole();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::find($this->validatedInput['user_id']);

        if (!$this->user) {
            $this->sendError('User not found', [], 404);
        }

        return $this;
    }



    private function updateRole()
    {
        $this->user->update(['role' => $this->validatedInput['role']]);

        return $this->sendSuccess($this->user->refresh(), 'User role updated successfully', 200);
    }
    

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string',
        ]);

        return $this;
    }
}
