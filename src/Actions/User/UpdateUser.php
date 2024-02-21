<?php

namespace Transave\ScolaBookstore\Actions\User;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;


class UpdateUser
{
    use ValidationHelper, ResponseHelper;

    private $request, $uploader, $user;
    private $validatedInput;

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
                ->checkIfNewEmail()
                ->uploadOrReplaceImage()
                ->checkUserRole()
                ->updateIfAuthor()
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

    private function checkIfNewEmail()
    {
        if ($this->user->isDirty('email')) {
            $this->validatedInput['email'] = $this->request['email'];
        }
        return $this;
    }

    private function uploadOrReplaceImage()
    {
        if (isset($this->request['profile_image']) && $this->request['profile_image']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['profile_image'], 'bookstore/profile', $this->user, 'profile_image');
            if ($response['success']) {
                $this->validatedInput['profile_image'] = $response['upload_url'];
            }
        }
        return $this;
    }

    private function checkUserRole()
    {
        if (Arr::exists($this->validatedInput, 'role')) {
            if (auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin') {
                $this->validatedInput['role'] = $this->request['role'];
            }else {
                abort(401, 'role can only be changed by admin or super admin');
            }
        }
        return $this;
    }

    private function updateIfAuthor()
    {
        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'author') {
            Author::query()->updateOrCreate([
                'user_id' => $this->validatedInput['user_id'],
            ]);
        }
        return $this;
    }

    private function updateUser()
    {
        $this->user->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->user->refresh(), 'user updated');
    }

    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|in:super-admin,admin,author,user,reviewer',
            "bio" => 'sometimes|required|string',
            "profile_image" => 'sometimes|required|file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            "phone" => 'sometimes|required|string|max:20|Min:11',
        ]);
        $this->validatedInput = Arr::except($data, ['profile_image', 'role']);
        return $this;
    }
}
