<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\State;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'capital' => $this->faker->city,
            'country_id' => Country::factory(),
        ];
    }
}