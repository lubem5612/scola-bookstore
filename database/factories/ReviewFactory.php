<?php


namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Http\Models\Review;
use Transave\ScolaBookstore\Http\Models\User;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'resource_id' => Resource::factory(),
            'review' => $this->faker->sentence,
            'rating' => $this->faker->randomDigit(),
        ];
    }
}