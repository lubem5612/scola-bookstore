<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Cart\UpdateCart;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateCartTest extends TestCase
{
    private $user;
    private $cart;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->cart = Cart::factory()->create();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */
    public function test_can_update_cart_via_action()
    {
      $this->request = [
            'cart_item_id' => Cart::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 10),

        ];
        $response = (new UpdateCart($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    /** @test */
//     public function test_can_update_cart_via_api()
//     {
//         $response = $this->json('PATCH', "bookstore/carts/{$this->cart->id}", $this->request, ['Accept' => 'application/json']);
//         $array = json_decode($response->getContent(), true);
//         $this->assertTrue($array['success']);
//         $this->assertNotNull($array['data']);
//     }

    private function testData()
    {
       $this->faker = Factory::create();
        $this->request = [
            'cart_item_id' => Cart::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 10),

        ];
    }
}
