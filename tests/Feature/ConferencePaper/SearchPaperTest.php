<?php

namespace Transave\ScolaBookstore\Tests\Feature\ConferencePaper;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchPaperTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_conference_paper_using_query_parameters()
    {

        $searchTerm = 'twilight';
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/papers?search={$searchTerm}");
        $response->assertStatus(200);

        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }


    /** @test */
    function can_fetch_paginated_conference_papers()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json("GET", "/bookstore/papers?per_page={$perPage}");

        $response->assertStatus(200);

        $array = $response->json(); 
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount($perPage, $array['data']['data']);
    }

    private function testData($search = null)
    {
        $faker = Factory::create();
        ConferencePaper::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->state(['first_name' => $faker->name . ' ' . $search]))
            ->create();

        ConferencePaper::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->create())
            ->create();
    }
}