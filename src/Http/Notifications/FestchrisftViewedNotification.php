<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Http\Models\User;

class FestchrisftViewedNotification extends Notification
{
    public User $user;
    public Festchrisft $festchrisft;


    public function __construct(User $user, Festchrisft $festchrisft)
    {
        $this->user = $user;
        $this->festchrisft = $festchrisft;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'festchrisft_id' => $this->festchrisft->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->festchrisft->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}