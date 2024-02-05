<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\ResourceCategory;
use Transave\ScolaBookstore\Http\Models\User;

class ResearchResourceViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public ResourceCategory $researchResource;

    public function __construct(User $user, ResourceCategory $researchResource)
    {
        $this->user = $user;
        $this->researchResource = $researchResource;
    }

}