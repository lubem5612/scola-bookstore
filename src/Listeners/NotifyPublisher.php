<?php

namespace Transave\ScolaBookstore\Listeners;

use Transave\ScolaBookstore\Events\BookViewed;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Notifications\BookViewedNotification;

class NotifyPublisher
{
    public function __construct()
    {
        //
    }

    public function handle(BookViewed $event)
    {
        // Get the admin user
        $admin = User::where('role', 'superAdmin')->first();

        if ($admin) {
            // Send a notification to the admin
            $admin->notify(new BookViewedNotification($event->user, $event->book)); // Use the correct namespace and class name
        }
    }
//        // Check if the book has a publisher
//        if ($event->book->publisher) {
//            // Send a notification to the book's publisher
//            $event->book->publisher->notify(new \Transave\ScolaBookstore\Http\Notifications\BookViewedNotification($event->user, $event->book));
//        }
}