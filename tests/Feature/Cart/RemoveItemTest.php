<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;


class RemoveItemTest extends TestCase
{
 
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);

    }

    public function testRemoveItemFromCart()
    {
        $cartItem = Cart::factory()->create();

        $response = $this->delete("bookstore/carts/{$cartItem->id}");

        $array = json_decode($response->getContent(), true);
        dd($array);
        $this->assertEquals(true, $array['success']);
    }
}
