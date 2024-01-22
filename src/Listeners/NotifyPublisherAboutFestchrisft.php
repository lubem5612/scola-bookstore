<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\FestchrisftViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\FestchrisftViewedNotification;

class NotifyPublisherAboutFestchrisft
{
    public function __construct()
    {
        //
    }

    public function handle(FestchrisftViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new FestchrisftViewedNotification($event->user, $event->festchrisft)); // Use the correct namespace and class name
        }
  
       // Check if the festchrisft has a publisher
       if ($event->festchrisft->publisher) {
           // Send a notification to the festchrisft's publisher
           $event->festchrisft->publisher->notify(new FestchrisftViewedNotification($event->user, $event->festchrisft));
       }
    }
}