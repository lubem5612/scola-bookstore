<?php

namespace Transave\ScolaBookstore\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\User;

class ViewBookNotification extends Notification
{

    public User $user;
    public Book $book;


    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }
}