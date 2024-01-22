<?php

namespace Transave\ScolaBookstore\Http\Notifications;

use Illuminate\Notifications\Notification;
use Transave\ScolaBookstore\Http\Models\Report;
use Transave\ScolaBookstore\Http\Models\User;

class ReportViewedNotification extends Notification
{
    public User $user;
    public Report $report;


    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'report_id' => $this->report->id,
            'message' => $this->user->first_name . " " . $this->user->last_name . ' just viewed ' . $this->report->title,
        ];
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }




}