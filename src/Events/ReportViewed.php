<?php
namespace Transave\ScolaBookstore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Report;
use Transave\ScolaBookstore\Http\Models\User;

class ReportViewed
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Report $report;

    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

}