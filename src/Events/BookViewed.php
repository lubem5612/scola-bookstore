<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Http\Models\User;

class BookViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Resource $book;

    public function __construct(User $user, Resource $book)
    {
        $this->user = $user;
        $this->book = $book;
    }
}