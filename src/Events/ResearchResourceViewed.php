<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\ResearchResource;
use Transave\ScolaBookstore\Http\Models\User;

class ResearchResourceViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public ResearchResource $researchResource;

    public function __construct(User $user, ResearchResource $researchResource)
    {
        $this->user = $user;
        $this->researchResource = $researchResource;
    }

}