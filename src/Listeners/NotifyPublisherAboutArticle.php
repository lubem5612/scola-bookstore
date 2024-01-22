<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\ArticleViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\ArticleViewedNotification;

class NotifyPublisherAboutArticle
{
    public function __construct()
    {
        //
    }

    public function handle(ArticleViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin','publisher')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new ArticleViewedNotification($event->user, $event->article)); // Use the correct namespace and class name
        }
  
       // Check if the article has a publisher
       if ($event->article->publisher) {
           // Send a notification to the article's publisher
           $event->article->publisher->notify(new MonographViewedNotification($event->user, $event->article));
       }
    }
}