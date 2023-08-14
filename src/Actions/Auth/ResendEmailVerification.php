<?php

namespace Transave\ScolaBookstore\Actions\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\WelcomeNotification;

class ResendEmailVerification
{
    use ResponseHelper;
    private $token;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function execute()
    {
        try {
            return $this
                ->setToken()
                ->saveToken()
                ->sendNotification()
                ->sendSuccess(null, 'token resend successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setToken(): static
    {
        $this->token = rand(100000, 999999);
        return $this;
    }

    private function saveToken()
    {
        if ($this->user->is_verified) {
            return $this->sendSuccess(null, 'user already verified');
        }
        $updated = $this->user->update([
            "token" => $this->token,
            "email_verified_at" => Carbon::now()
        ]);
        return $this;
    }

    private function sendNotification(): static
    {
        try {
            Notification::route('mail', $this->user->email)
                ->notify(new WelcomeNotification([
                    "token" => $this->token,
                    "user" => $this->user
                ]));
        } catch (\Exception $exception) {
        }
        return $this;
    }
}