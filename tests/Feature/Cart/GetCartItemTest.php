<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Transave\ScolaBookstore\Actions\Cart\GetCartItem;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class GetCartItemTest extends TestCase
{

    use RefreshDatabase;
    protected $user;

  
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role'=> 'user']);
        Sanctum::actingAs($this->user);
    }


    public function test_can_get_cart_items_via_action()
    {

        $cartItems = Cart::factory(3)->create(['user_id' => $this->user->id]);

        $result = (new GetCartItem(['id' => $this->user->id]))->execute();

               dd($result);
    }
}
