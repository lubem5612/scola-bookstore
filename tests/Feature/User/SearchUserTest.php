<?php

namespace Transave\ScolaBookstore\Tests\Feature\User;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_users_using_query_parameters()
    {
        $searchTerm = 'jon';
        $this->testData($searchTerm);
        $response = $this->json('GET', "bookstore/users?search={$searchTerm}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }

    /** @test */
    function can_search_users_using_time_intervals()
    {
        $start = Carbon::yesterday()->toDateString();
        $end = Carbon::tomorrow()->toDateString();
        $this->testData();
        $response = $this->json('GET', "bookstore/users?start={$start}&end={$end}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(7, $array['data']);
    }

    /** @test */
    function can_fetch_paginated_users()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json('GET', "bookstore/users?per_page={$perPage}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount($perPage, $array['data']['data']);
    }

    private function testData($search = null)
    {
        $faker = Factory::create();
        User::factory()
            ->count(3)
            ->state(['first_name' => $faker->name . ' ' . $search])
            ->create();

        User::factory()
            ->count(3)
            ->create();
    }
}