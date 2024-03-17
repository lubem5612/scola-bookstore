<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Reviewer;
use Transave\ScolaBookstore\Http\Models\User;

class ReviewerFactory extends Factory
{
    protected $model = Reviewer::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'specialization' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['approved', 'rejected', 'suspended']),
            'previous_projects' => json_encode(['1' => $this->faker->sentence, '2' => $this->faker->sentence])
        ];
    }
}