<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Actions\Cart\ClearCart;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ClearCartTest extends TestCase
{
 
    use RefreshDatabase;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role'=> 'user']);
        Sanctum::actingAs($this->user);
    }



    public function test_user_can_clear_cart_via_action()
    {

        $this->user->cart()->saveMany(Cart::factory(3)->make());

        $clearCart = new ClearCart();
        $result = $clearCart->execute($this->user->id);

        $array = json_decode($result->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertEquals(0, $this->user->cart()->count());

    }




   public function test_user_can_clear_cart_via_api()
    {

        $this->user->cart()->saveMany(Cart::factory(3)->make());

        $response = $this->delete("bookstore/carts/clear/{$this->user->id}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertEquals(0, $this->user->cart()->count());

    }

}
