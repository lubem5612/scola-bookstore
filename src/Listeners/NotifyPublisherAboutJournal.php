<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\JournalViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\JournalViewedNotification;

class NotifyPublisherAboutJournal
{
    public function __construct()
    {
        //
    }

    public function handle(JournalViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new JournalViewedNotification($event->user, $event->journal)); // Use the correct namespace and class name
        }
  
       // Check if the journal has a publisher
       if ($event->journal->publisher) {
           // Send a notification to the journal's publisher
           $event->journal->publisher->notify(new JournalViewedNotification($event->user, $event->journal));
       }
    }
}