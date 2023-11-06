<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\User;

class BookViewedNotification extends Notification
{
    public User $user;
    public Book $book;


    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->book->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}