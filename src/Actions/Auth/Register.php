<?php

namespace Transave\ScolaBookstore\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Reviewer;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Notifications\WelcomeNotification;



class Register
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private User $user;
    private $uploader;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try{
            return $this
                ->validateRequest()
                ->setUserPassword()
                ->setVerificationToken()
                ->setUserType()
                ->uploadProfileImage()
                ->setPreviousProjects()
                ->setReviewerStatus()
                ->createUser()
                ->createAuthor()
                ->createReviewer()
                ->sendNotification()
                ->sendSuccess($this->user->load('author', 'reviewer'), 'account created successfully');
        }catch (\Exception $exception){
            return $this->sendServerError($exception);
        }
    }

    private function setUserPassword(): self
    {
        $this->validatedInput['password'] = bcrypt($this->validatedInput['password']);
        return $this;
    }

    private function createUser(): self
    {
        $userData = Arr::only($this->validatedInput, [
            'role',
            'verification_token',
            'email_verified_at',
            'password',
            'first_name',
            'last_name',
            'email',
            'phone',
            'profile_image'
        ]);
        $this->user = User::query()->create($userData);
        return $this;
    }

    private function createAuthor()
    {
        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'author') {
            $authorData = Arr::only($this->validatedInput, [
                'department_id',
                'faculty_id',
                'specialization',
                'bio',
            ]);
            $authorData['user_id'] = $this->user->id;
            Author::query()->create($authorData);
        }
       return $this;
    }

    private function createReviewer()
    {
        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'reviewer') {
            $reviewerData = Arr::only($this->validatedInput, [
                'specialization',
                'status',
                'previous_projects'
            ]);
            $reviewerData['user_id'] = $this->user->id;
            Reviewer::query()->create($reviewerData);
        }
        return $this;
    }

    private function setReviewerStatus()
    {
        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'reviewer') {
            $this->validatedInput['status'] = 'pending';
        }
        return $this;
    }

    private function setUserType(): self

    {
        if (!Arr::exists($this->validatedInput, 'role')) {
            $this->validatedInput['role'] = 'user';
        }

        return $this;
    }

    private function setVerificationToken(): self
    {
        $this->validatedInput['verification_token'] = rand(100000, 999999);
        $this->validatedInput['email_verified_at'] = Carbon::now();
        return $this;
    }

    private function sendNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->validatedInput['verification_token'],
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }

    private function uploadProfileImage()
    {
        if (Arr::exists($this->request, 'profile_image') && $this->request['profile_image'])
        {
            $response = $this->uploader->uploadFile($this->request['profile_image'], 'bookstore/profile');
            if ($response['success']) $this->validatedInput['profile_image'] = $response['upload_url'];
        }
        return $this;
    }

    private function setPreviousProjects()
    {
        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'reviewer') {
            if (Arr::exists($this->request, 'previous_projects')
                && is_array($this->request['previous_projects'])
                && count($this->request['previous_projects']) > 0) {
                $this->validatedInput['previous_projects'] = json_encode($this->request['previous_projects']);
            }
        }
        return $this;
    }
//
//    private function setBankInformation()
//    {
//        if (Arr::exists($this->validatedInput, 'role') && $this->validatedInput['role'] == 'author') {
//            if (Arr::exists($this->request, 'bank_info') && $this->request['bank_info'])
//            {
//                $validator = $this->validate($this->request['bank_info'], [
//                    'bank_code' => 'required',
//                    'account_no' => 'required|string',
//                    'account_name' => 'required|string'
//                ]);
//                $this->validatedInput['bank_info'] = json_encode($this->request['bank_info']);
//            }
//        }
//        return $this;
//    }

    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,author,user,reviewer'],
            'password' => ['required', 'string'],
            'profile_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:3000'],
            "phone" => 'sometimes|required|string|max:20|Min:11',

            'specialization' => ['nullable', 'string', 'max:700'],
            'status' => ['nullable', 'string', 'in:approved,rejected,suspended,pending'],
            'previous_projects' => ['nullable', 'array'],
            'previous_projects.*' => ['nullable', 'string'],

            'department_id' => ['nullable', 'exists:departments,id'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'bio' => ['nullable', 'string'],
        ]);
        $this->validatedInput = Arr::except($data, ['profile_image', 'previous_projects']);
        return $this;
    }
}
