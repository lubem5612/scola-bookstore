<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\User;

class ReviewerRequestFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReviewerRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
               'user_id' => config('scola-bookstore.auth_model')::factory(),
               'specialization' => $this->faker->company,
               'year_of_project' => $this->faker->date(),
               'previous_projects' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
        ];
        
    }

}

