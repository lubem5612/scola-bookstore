<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Http\Models\User;

class JournalViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Journal $journal;

    public function __construct(User $user, Journal $journal)
    {
        $this->user = $user;
        $this->journal = $journal;
    }
}