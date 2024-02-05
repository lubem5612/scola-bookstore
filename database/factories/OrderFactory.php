<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;



    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       return [
           'user_id' => config('scola-bookstore.auth_model')::factory(),
           'order_date' => $this->faker->date(),
           'invoice_number' => 'INV-' . $this->faker->unique()->randomNumber(4,9),
           'delivery_status' => $this->faker->randomElement(['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled']),
           'order_status' => $this->faker->randomElement(['success', 'failed']),
           'payment_status' => $this->faker->randomElement(['Paid', 'Pending']),
           'payment_reference' => $this->faker->unique()->randomNumber(4,9),
           'total_amount' => $this->faker->randomFloat(2, 1000, 2000),
           'shipping_info' => $this->faker->randomElement(['address, fee, delivery_time']),
        ];
    }
}