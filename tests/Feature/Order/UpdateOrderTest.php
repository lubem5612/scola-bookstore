<?php

namespace Transave\ScolaBookstore\Tests\Feature\Order;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Order\UpdateOrder;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateOrderTest extends TestCase
{
    private $user;
    private $order;
    private $request;
    private $faker;


    public function setUp(): void
    {
        parent::setUp();
        $this->order = Order::factory()->create();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }



    /** @test */
    public function test_can_update_order_via_action()
    {
        $response = (new UpdateOrder($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }




    /** @test */
    public function test_can_update_order_via_api()
    {
        $order = Order::query()->inRandomOrder()->first();
        $response = $this->json('PUT', "bookstore/orders/{$order->id}", $this->request, ['Accept' => 'application/json']);
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    private function testData()
    {
        $this->faker = Factory::create();

        $this->request = [
            'order_id' => $this->order->id,
            'user_id' => $this->user->id,
            'book_id' => Book::factory()->create()->id,
            'amount' => $this->faker->randomNumber(4, 9),
            'total_amount' => $this->faker->randomNumber(4, 9),
            'invoice_no' => 'INV-' . $this->faker->unique()->randomNumber(4),
            'status' => $this->faker->randomElement(['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled']),

        ];
    }
}