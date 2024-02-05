<?php

namespace Transave\ScolaBookstore\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Http\Models\User;

class ViewBookNotification extends Notification
{

    public User $user;
    public Resource $book;


    public function __construct(User $user, Resource $book)
    {
        $this->user = $user;
        $this->book = $book;
    }
}