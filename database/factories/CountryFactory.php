<?php


namespace Transave\ScolaBookstore\Database\Factories;



use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Country;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        return [
            'name' => $this->faker->country,
            'code' => $this->faker->countryISOAlpha3
        ];
    }
}
