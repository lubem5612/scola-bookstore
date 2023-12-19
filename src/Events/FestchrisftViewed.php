<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Http\Models\User;

class FestchrisftViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Festchrisft $festchrisft;

    public function __construct(User $user, Festchrisft $festchrisft)
    {
        $this->user = $user;
        $this->festchrisft = $festchrisft;
    }
}