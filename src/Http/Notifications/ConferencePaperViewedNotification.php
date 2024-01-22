<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\User;

class ConferencePaperViewedNotification extends Notification
{
    public User $user;
    public ConferencePaper $conferencePaper;


    public function __construct(User $user, ConferencePaper $conferencePaper)
    {
        $this->user = $user;
        $this->conferencePaper = $conferencePaper;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'report_id' => $this->conferencePaper->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->conferencePaper->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}