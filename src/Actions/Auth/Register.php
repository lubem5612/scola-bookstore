<?php

namespace Transave\ScolaBookstore\Actions\Auth;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
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

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    
    public function execute()
    {
        try{
            return $this
                ->validateRequest()
                ->setUserPassword()
                ->setVerificationToken()
                ->setUserType()
                ->createUser()
                ->sendNotification()
                ->sendSuccess($this->user, 'account created successfully');
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
        $this->user = User::query()->create($this->validatedInput);
        return $this;
    }

    private function setUserType(): self
    {
        if (!array_key_exists('role', $this->validatedInput)) {
            $this->validatedInput['role'] = 'user';
        }
        return $this;
    }

    private function setVerificationToken(): self
    {
        $this->validatedInput['token'] = rand(100000, 999999);
        $this->validatedInput['email_verified_at'] = Carbon::now();
        return $this;
    }



    private function sendNotification()
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->validatedInput['token'],
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }

    public function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'role' => ['string', 'in:superAdmin,admin,publisher,user'],
            'password' => ['required', 'string'],
        ]);
        $this->validatedInput = Arr::only($data, ['first_name', 'last_name', 'email', 'password']);
        return $this;
    }
}
