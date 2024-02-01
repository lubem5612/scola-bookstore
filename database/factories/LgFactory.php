<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\State;

class LgFactory extends Factory
{
    protected $model = Lg::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'state_id' => State::factory(),
        ];
    }
}