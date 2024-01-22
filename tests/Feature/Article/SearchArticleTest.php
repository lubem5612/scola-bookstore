<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchArticleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_article_using_query_parameters()
    {

        $searchTerm = 'twilight';
        $this->testData($searchTerm);
        $response = $this->json('GET', "/bookstore/articles?search={$searchTerm}");
        $response->assertStatus(200);
        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }

    /** @test */
    function can_search_articles_using_time_intervals()
    {
        $start = Carbon::yesterday()->toDateString();
        $end = Carbon::tomorrow()->toDateString();
        $this->testData();
        $response = $this->json("GET", "/bookstore/articles?start={$start}&end={$end}");
        $response->assertStatus(200);
        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(6, $array['data']);
    }

    /** @test */
    function can_fetch_paginated_articles()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json("GET", "/bookstore/articles?per_page={$perPage}");
        $response->assertStatus(200);
        $array = $response->json();
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount($perPage, $array['data']['data']);
    }

    private function testData($search = null)
    {
        $faker = Factory::create();
        Article::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->state(['first_name' => $faker->name . ' ' . $search]))
            ->create();

        Article::factory()
            ->count(3)
            ->for(config('scola-bookstore.auth_model')::factory()->create())
            ->create();
    }
}