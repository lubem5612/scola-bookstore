<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;
use Transave\ScolaBookstore\Http\Models\Resource;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'invoice_number' => Str::random(10),
            'resource_id' => Resource::factory(),
            'quantity' => $this->faker->randomFloat(2, 1, 10),
            'unit_price' => $this->faker->randomNumber(4,9),
            'total_amount' => $this->faker->randomNumber(4,9),
            'discount' => $this->faker->randomFloat([0.1, 0.99]),
            'discount_type' => $this->faker->randomElement(['amount', 'percent']),
        ];
    }
}