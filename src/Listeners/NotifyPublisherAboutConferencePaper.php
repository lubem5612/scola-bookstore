<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\ConferencePaperViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\ConferencePaperViewedNotification;

class NotifyPublisherAboutConferencePaper
{
    public function __construct()
    {
        //
    }

    public function handle(ConferencePaperViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new ConferencePaperViewedNotification($event->user, $event->conferencePaper)); // Use the correct namespace and class name
        }
  
       // Check if the conferencePaper has a publisher
       if ($event->conferencePaper->publisher) {
           // Send a notification to the conferencePaper's publisher
           $event->conferencePaper->publisher->notify(new ConferencePaperViewedNotification($event->user, $event->conferencePaper));
       }
    }
}