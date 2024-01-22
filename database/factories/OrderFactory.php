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
           'resource_id' => \Illuminate\Support\Str::uuid(),
           'quantity' => 2,
           'order_date' => $this->faker->date(),
           'unit_price' => $this->faker->randomNumber(4,9),
           'total_amount' => $this->faker->randomNumber(4,9),
           'invoice_no' => 'INV-' . $this->faker->unique()->randomNumber(4,9),
           'status' => $this->faker->randomElement(['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled']),
           'resource_type' => $this->faker->randomElement(['Monograph', 'Report', 'Book', 'Journal', 'ResearchResource', 'Festchrisft', 'ConferencePaper', 'Article']),
        ];
    }
}
