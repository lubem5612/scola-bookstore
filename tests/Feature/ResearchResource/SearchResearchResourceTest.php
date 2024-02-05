<?php

namespace Transave\ScolaBookstore\Tests\Feature\ResearchResource;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\ResourceCategory;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchResearchResourceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
        $this->testData();
    }

    /** @test */
    function can_search_research_resource_by_user_first_name()
    {

        $searchTerm = $this->first_name;
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/research_resources?search={$searchTerm}");
        $response->assertStatus(200);

        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(16, $array['data']);
    }



     function can_search_research_resource_by_category()
    {

        $searchTerm = $this->category;
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/research_resources?search={$searchTerm}");
        $response->assertStatus(200);

        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(16, $array['data']);
    }


         function can_search_research_resource_by_publisher()
    {

        $searchTerm = $this->publisher;
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/research_resources?search={$searchTerm}");
        $response->assertStatus(200);
        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(16, $array['data']);
    }


    /** @test */
    function can_fetch_paginated_research_resources()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json("GET", "/bookstore/research_resources?per_page={$perPage}");

        $response->assertStatus(200);

        $array = $response->json(); 
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }


    private function testData($search = null)
    {
        $faker = Factory::create();
        $this->first_name = ResourceCategory::factory()->count(2)
               ->for(config('scola-bookstore.auth_model')::factory()
               ->state(['first_name' => "michael" . ' ' . $search]))
               ->create();
       
       
      $this->category = ResourceCategory::factory()->count(2)
               ->for(Category::factory()
               ->state(['name' => "michael" . ' ' . $search]))
               ->create();

      $this->publisher = ResourceCategory::factory()->count(2)
               ->for(Author::factory()
               ->state(['name'=>$faker->company . ' ' . $search]))
               ->create();

        ResourceCategory::factory()
            ->count(2)
            ->for(config('scola-bookstore.auth_model')::factory()->create())
            ->create();
    }
}