<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Http\Models\User;

class MonographViewedNotification extends Notification
{
    public User $user;
    public Monograph $monograph;


    public function __construct(User $user, Monograph $monograph)
    {
        $this->user = $user;
        $this->monograph = $monograph;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'monograph_id' => $this->monograph->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->monograph->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}