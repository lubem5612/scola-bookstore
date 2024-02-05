<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Notification;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'sender_id' => config('scola-bookstore.auth_model')::factory(),
            'title' => $this->faker->title,
            'message' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['shipping', 'welcome', 'alert']),
        ];
    }
}