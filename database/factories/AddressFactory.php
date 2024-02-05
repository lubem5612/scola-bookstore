<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'name' => $this->faker->name,
            'is_default' => $this->faker->randomElement([true, false]),
        ];
    }
}