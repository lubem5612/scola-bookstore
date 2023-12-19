<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\User;

class ConferencePaperViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public ConferencePaper $conferencePaper;

    public function __construct(User $user, ConferencePaper $conferencePaper)
    {
        $this->user = $user;
        $this->conferencePaper = $conferencePaper;
    }
}