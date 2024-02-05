<?php

namespace Transave\ScolaBookstore\Tests\Feature\Festchrisft;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchFestchrisftTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
        $this->testData();
    }

    /** @test */
    function can_search_festchrisft_by_user_first_name()
    {

        $searchTerm = $this->first_name;
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/festchrisfts?search={$searchTerm}");
        $response->assertStatus(200);

        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(16, $array['data']);
    }



     function can_search_festchrisft_by_category()
    {

        $searchTerm = $this->category;
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/festchrisfts?search={$searchTerm}");
        $response->assertStatus(200);

        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(16, $array['data']);
    }


    /** @test */
    function can_fetch_paginated_festchrisfts()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json("GET", "/bookstore/festchrisfts?per_page={$perPage}");

        $response->assertStatus(200);

        $array = $response->json(); 
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }


    private function testData($search = null)
    {
        $faker = Factory::create();
        $this->first_name = Festchrisft::factory()->count(2)
               ->for(config('scola-bookstore.auth_model')::factory()
               ->state(['first_name' => "michael" . ' ' . $search]))
               ->create();
       
       
      $this->category = Festchrisft::factory()->count(2)
               ->for(Category::factory()
               ->state(['name' => "michael" . ' ' . $search]))
               ->create();

        Festchrisft::factory()->count(2)
               ->for(Author::factory()
               ->state(['name'=>$faker->company . ' ' . $search]))
               ->create();

        Festchrisft::factory()
            ->count(2)
            ->for(config('scola-bookstore.auth_model')::factory()->create())
            ->create();
    }
}