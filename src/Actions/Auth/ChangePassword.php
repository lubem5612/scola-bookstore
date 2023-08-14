<?php

namespace Transave\ScolaBookstore\Actions\Auth;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\User;

class ChangePassword
{
    use ResponseHelper, ValidationHelper;

    private array $request;
    private User $user;

    public function __construct(User $user, array $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validatePassword()
                ->updatePassword();
        } catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function validatePassword(): static
    {
        $this->validate($this->request, [
            'password' => 'string|min:6',
        ]);
        return $this;
    }

    private function updatePassword()
    {
        $this->user->password = bcrypt($this->request['password']);
        $this->user->save();
        return $this->sendSuccess($this->user, 'Password changed successfully');
    }
}
