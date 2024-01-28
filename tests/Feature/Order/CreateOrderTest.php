<?php

namespace Transave\ScolaBookstore\Tests\Feature\Order;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Order\CreateOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_Order()
    {
        $orderData = [
            'id'=> factory(\Transave\ScolaBookstore\Http\Models\Order::class)->create()->id,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'order_date' => $this->faker->date(),
            'invoice_number' => 'INV-' . $this->faker->unique()->randomNumber(4,9),
            'status' => $this->faker->randomElement(['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled']),
        ];

        $orderItemsData = [
            [
                'order_id' => $orderData->id, 
                'resource_id' => \Illuminate\Support\Str::uuid(),
                'invoice_number' => $orderData->invoice_number, 
                'quantity' => $this->faker->randomFloat(2, 1, 10), 
                'unit_price' => $this->faker->randomNumber(4,9), 
                'total_amount' => $this->faker->randomNumber(4,9),
            ],
        ];
       
        $response = $this->json('POST', 'bookstore/orders', [
            'orders' => $orderData,
            'order_items' => $orderItemsData,
        ]);

        $response->assertStatus(201); 
        $this->assertDatabaseHas('orders', $orderData);

        foreach ($orderItemsData as $itemData) {
            $this->assertDatabaseHas('order_items', $itemData);
        }
    }
}
