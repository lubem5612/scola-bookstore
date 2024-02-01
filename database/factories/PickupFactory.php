<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Pickup;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\State;
use Transave\ScolaBookstore\Http\Models\Lg;

class PickupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pickup::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address,
            'recipient_first_name' => $this->faker->name,
            'recipient_last_name' => $this->faker->name,
            'alternative_phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'order_id' => Order::factory(),
            'country_id' => Country::factory(),
            'state_id' => State::factory(),
            'lg_id' => Lg::factory(),
            'postal_code' => $this->faker->countryISOAlpha3

        ];
    }

}