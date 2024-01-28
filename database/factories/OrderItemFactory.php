<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;

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
         $order = factory(\Transave\ScolaBookstore\Http\Models\Order::class)->create();
        return [
            'order_id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'resource_id' => \Illuminate\Support\Str::uuid(),
            'quantity' => $this->faker->randomFloat(2, 1, 10),
            'unit_price' => $this->faker->randomNumber(4,9),
            'total_amount' => $this->faker->randomNumber(4,9),

        ];
    }
}