<?php

namespace Transave\ScolaBookstore\Tests\Feature\Order;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchOrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_orders_using_query_parameters()
    {

        $searchTerm = 'delivered';
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/orders?search={$searchTerm}");
        $response->assertStatus(200);
        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }


    private function testData($search = null)
    {
        $faker = Factory::create();
        Order::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->state(['first_name' => $faker->name . ' ' . $search]))
            ->create();

        Order::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->create())
            ->create();
    }
}