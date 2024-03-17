<?php

namespace Transave\ScolaBookstore\Actions\User;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Reviewer;
use Transave\ScolaBookstore\Http\Models\User;

class DeleteUser
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private User $user;
    private $uploader;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setUser()
                ->deleteProfileImage()
                ->deleteRoles()
                ->deleteUser();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteUser()
    {
        $this->user->delete();
        return $this->sendSuccess(null, 'user deleted successfully');
    }

    private function deleteProfileImage()
    {
        if ($this->user->profile_image) {
            $this->uploader->deleteFile($this->user->profile_image);
        }
        return $this;
    }

    private function deleteRoles()
    {
        if ($this->user->role == 'author') {
            Author::query()->where('user_id', $this->user->id)->delete();
        }elseif ($this->user->role == 'reviewer') {
            Reviewer::query()->where('user_id', $this->user->id)->delete();
        }
        return $this;
    }

    private function setUser() :self
    {
        $this->user = User::query()->find($this->request['id']);
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'id' => 'required|exists:users,id'
        ]);
        return $this;
    }
}