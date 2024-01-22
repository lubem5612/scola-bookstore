<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\ResearchResource;
use Transave\ScolaBookstore\Http\Models\User;

class ResearchResourceViewedNotification extends Notification
{
    public User $user;
    public ResearchResource $researchResource;


    public function __construct(User $user, ResearchResource $researchResource)
    {
        $this->user = $user;
        $this->researchResource = $researchResource;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'ResearchResource_id' => $this->researchResource->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->researchResource->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}