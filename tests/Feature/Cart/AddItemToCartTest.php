<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
// use Illuminate\Http\Testing\File;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Cart\AddToCart;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Tests\TestCase;

class AddItemToCartTest extends TestCase
{
    private $user;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */
    public function can_create_cart_via_action()
    {
        $response = (new AddToCart($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    /** @test */
    public function can_create_cart_via_api()
    {
        $response = $this->json('POST', 'bookstore/carts', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $array = json_decode($response->getContent(), true);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }


    private function testData()
    {
        $this->faker = Factory::create();
        $this->request = [
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'resource_id' => $this->faker->uuid,
            'quantity' => $this->faker->numberBetween(1, 10),
            'resource_type' => $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
