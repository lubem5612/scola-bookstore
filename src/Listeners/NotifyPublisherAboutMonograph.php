<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\MonographViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\MonographViewedNotification;

class NotifyPublisherAboutMonograph
{
    public function __construct()
    {
        //
    }

    public function handle(MonographViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new MonographViewedNotification($event->user, $event->monograph)); // Use the correct namespace and class name
        }
  
       // Check if the Monograph has a publisher
       if ($event->monograph->publisher) {
           // Send a notification to the Monograph's publisher
           $event->monograph->publisher->notify(new MonographViewedNotification($event->user, $event->monograph));
       }
    }
}