<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Http\Models\User;

class MonographViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Monograph $monograph;

    public function __construct(User $user, Monograph $monograph)
    {
        $this->user = $user;
        $this->monograph = $monograph;
    }
}