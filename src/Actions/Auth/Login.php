<?php

namespace Transave\ScolaBookstore\Actions\Auth;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;

class Login
{
    
    use ResponseHelper, ValidationHelper;
    private $data;
    private $username;
    private $validatedInput;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute()
    {
        try {
            return $this
                ->validateLoginData()
                ->setUsername()
                ->authenticateUser();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function authenticateUser()
    {
        $isAuth = auth()->guard('api')->attempt([$this->username => $this->validatedInput['email'], 'password' => $this->validatedInput['password']]);
        if ($isAuth) {
            $token = auth()->guard('api')->user()->createToken(uniqid())->plainTextToken;
            return $this->sendSuccess($token, 'login successful');
        }
        return $this->sendError('authentication failed', [], 401);
    }

    private function setUsername()
    {
        if(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->username = 'email';
        }
        else {
            return $this->sendError('wrong email', [], 401);
        }
        return $this;
    }

    private function validateLoginData()
    {
        $this->validatedInput = $this->validate($this->data, [
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => ['required', 'string', 'max:60']
        ]);

        return $this;
    }

}