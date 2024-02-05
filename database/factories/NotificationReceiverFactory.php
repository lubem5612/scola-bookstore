<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Notification;
use Transave\ScolaBookstore\Http\Models\NotificationReceiver;

class NotificationReceiverFactory extends Factory
{
    protected $model = NotificationReceiver::class;

    public function definition()
    {
        return [
            'receiver_id' => config('scola-bookstore.auth_model')::factory(),
            'notification_id' => Notification::factory(),
        ];
    }
}