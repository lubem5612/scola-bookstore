<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Sale;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;


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
            'total_amount' => $this->faker->randomNumber(4,9),

        ];
    }
}