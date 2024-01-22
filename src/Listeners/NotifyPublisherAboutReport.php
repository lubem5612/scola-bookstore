<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\ReportViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\ReportViewedNotification;

class NotifyPublisherAboutReport
{
    public function __construct()
    {
        //
    }

    public function handle(ReportViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new ReportViewedNotification($event->user, $event->report)); // Use the correct namespace and class name
        }
  
       // Check if the report has a publisher
       if ($event->report->publisher) {
           // Send a notification to the report's publisher
           $event->report->publisher->notify(new ReportViewedNotification($event->user, $event->report));
       }
    }
}