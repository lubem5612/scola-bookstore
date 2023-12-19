<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Http\Models\User;

class JournalViewedNotification extends Notification
{
    public User $user;
    public Journal $journal;


    public function __construct(User $user, Journal $journal)
    {
        $this->user = $user;
        $this->Journal = $journal;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'journal_id' => $this->journal->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->journal->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}