<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\ResearchResourceViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\ResearchResourceViewedNotification;

class NotifyPublisherAboutResearchResource
{
    public function __construct()
    {
        //
    }

    public function handle(ResearchResourceViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new ResearchResourceViewedNotification($event->user, $event->researchResource)); // Use the correct namespace and class name
        }
  
       // Check if the ResearchResource has a publisher
       if ($event->researchResource->publisher) {
           // Send a notification to the ResearchResource's publisher
           $event->researchResource->publisher->notify(new ResearchResourceViewedNotification($event->user, $event->researchResource));
       }
    }
}