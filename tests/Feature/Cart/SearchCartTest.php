<?php

namespace Transave\ScolaBookstore\Tests\Feature\Cart;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Tests\TestCase;


class SearchCartTest extends TestCase
{
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $user = config('scola-bookstore.auth_model')::factory()->create();
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_fetch_all_carts()
    {
        $this->getTestData();
        $response = $this->json('GET', 'bookstore/carts');
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
        $this->assertCount(20, $arrayData['data']);
    }



    /** @test */
    function can_fetch_carts_with_search_term()
    {
        $this->getTestData('Journal');
        $response = $this->json('GET', 'bookstore/carts?search=Journal');
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        dd($arrayData);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    private function getTestData($resource_type=null)
    {
        $search = is_null($resource_type)? $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']): $resource_type;

        Cart::factory()->count(10)->for(User::factory())->create();
        Cart::factory()->count(10)->for(User::factory())->create(['resource_type' => $search]);
    }
}