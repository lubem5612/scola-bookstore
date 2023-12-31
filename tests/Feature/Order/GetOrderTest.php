<?php

namespace Transave\ScolaBookstore\Tests\Feature\Order;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Tests\TestCase;

class GetOrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Order::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_get_an_order_with_specific_id()
    {
        $order = Order::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/orders/{$order->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['user_id'], $order->user_id);
        $this->assertEquals($array['data']['book_id'], $order->book_id);
    }
}