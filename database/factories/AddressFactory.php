<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Address;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\State;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'address' => $this->faker->sentence,
            'country_id' => Country::factory(),
            'state_id' => State::factory(),
            'lg_id' => Lg::factory(),
            'postal_code' => $this->faker->postcode,
            'is_default' => $this->faker->randomElement([true, false]),
        ];
    }
}