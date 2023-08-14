<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\School;


class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition()
    {
        return [
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
        ];
    }
}
