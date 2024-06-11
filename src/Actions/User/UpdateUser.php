<?php

namespace Transave\ScolaBookstore\Actions\User;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Reviewer;
use Transave\ScolaBookstore\Http\Models\User;


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
                ->checkIfPhoneIsNew()
                ->uploadOrReplaceImage()
                ->checkUserRole()
                ->setPreviousProjects()
                ->changeReviewerStatus()
                ->updateAuthor()
                ->updateReviewer()
                ->updateUser()
                ->sendSuccess($this->user->refresh()->load('author', 'reviewer'), 'user updated');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setUser()
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
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

    private function changeReviewerStatus()
    {
        if (Arr::exists($this->validatedInput, 'status')) {
            if (auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin') {
                $this->validatedInput['status'] = $this->request['status'];
            }else {
                abort(401, 'status can only be changed by admin or super admin');
            }
        }
        return $this;
    }

    private function checkIfPhoneIsNew()
    {
        if ($this->user->isDirty('phone')) {
            $this->validatedInput['phone'] = $this->request['phone'];
        }
        return $this;
    }

    private function updateUser()
    {
        $userData = Arr::only($this->validatedInput, [
            'role',
            'first_name',
            'last_name',
            'profile_image',
            'phone'
        ]);
        $this->user->fill($userData)->save();
        return $this;
    }

    private function updateAuthor()
    {
        if ($this->user->role == 'author') {
            $authorData = Arr::only($this->validatedInput, [
                'department_id',
                'faculty_id',
                'specialization',
                'bio',
            ]);
           $author = Author::query()->where('user_id', $this->user->id)->first();
           $author->fill($authorData)->save();
        }
        return $this;
    }

    private function updateReviewer()
    {
        if ($this->user->role == 'reviewer') {
            $reviewerData = Arr::only($this->validatedInput, [
                'specialization',
                'status',
                'previous_projects'
            ]);
            $reviewer = Reviewer::query()->where('user_id', $this->user->id)->first();
            $reviewer->fill($reviewerData)->save();
        }
        return $this;
    }

    private function setPreviousProjects()
    {
        if ($this->user->role == 'reviewer') {
            if (Arr::exists($this->request, 'previous_projects')
                && is_array($this->request['previous_projects'])
                && count($this->request['previous_projects']) > 0) {
                $this->validatedInput['previous_projects'] = json_encode($this->request['previous_projects']);
            }
        }
        return $this;
    }

    private function validateRequest()
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|in:super-admin,admin,author,user,reviewer',
            "bio" => 'sometimes|required|string',
            "profile_image" => 'sometimes|required|file|max:5000|mimes:png,jpeg,jpg,gif,webp',
            "phone" => 'sometimes|required|string|max:20|Min:11',

            'specialization' => ['nullable', 'string', 'max:700'],
            'status' => ['nullable', 'string', 'in:approved,rejected,suspended'],
            'previous_projects' => ['nullable', 'array'],
            'previous_projects.*' => ['nullable', 'string'],

            'department_id' => ['nullable', 'exists:departments,id'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
        ]);
        $this->validatedInput = Arr::except($data, ['profile_image', 'role', 'previous_projects', 'status']);
        return $this;
    }
}
