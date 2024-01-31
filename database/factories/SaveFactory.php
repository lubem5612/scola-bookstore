<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Save;

class SaveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Save::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'resource_id' => $this->faker->uuid,
            'resource_type' => $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']),
        ];
    }
}