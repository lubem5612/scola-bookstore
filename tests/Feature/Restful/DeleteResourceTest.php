<?php

namespace Transave\ScolaBookstore\Tests\Feature\Restful;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\OrderDetail;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteResourceTest extends TestCase
{
    private $user, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create(['email' => 'sampledata@test.com', 'password' => bcrypt('sample1234'),]);
        Sanctum::actingAs($this->user);
    }


    /** @test */

    public function can_delete_specified_category()
    {
        Category::factory()->count(10)->create();
        $category = Category::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/categories/{$category->id}");

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_publisher()
    {
        Publisher::factory()->count(10)->create();
        $publisher = Publisher::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/publishers/{$publisher->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    function can_delete_cart_with_specific_id()
    {
        Cart::factory()->count(10)->create();
        $cart = Cart::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/carts/{$cart->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }

    /** @test */
    function can_delete_orderdetails_with_specific_id()
    {
        OrderDetail::factory()->count(10)->create();
        $orderdetail = OrderDetail::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/orderdetails/{$orderdetail->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }


    /** @test */
    function can_delete_school_with_specific_id()
    {
        School::factory()->count(10)->create();
        $school = School::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/schools/{$school->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }

    /** @test */
    function can_delete_saves_with_specific_id()
    {
        Save::factory()->count(10)->create();
        $save = Save::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/saves/{$save->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }
}