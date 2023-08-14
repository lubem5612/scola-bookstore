<?php

namespace Transave\ScolaBookstore\Actions\User;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\User;

class GetUser
{
    use ValidationHelper, ResponseHelper;
    private array $request;
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
                ->sendSuccess($this->user, 'user fetched successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser(): self
    {
        $this->user = User::query()
            ->with('school')
            ->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:users,id'
        ]);
        return $this;
    }
}
