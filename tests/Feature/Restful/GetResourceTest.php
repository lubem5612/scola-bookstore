<?php

namespace Transave\ScolaBookstore\Tests\Feature\Restful;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class GetResourceTest extends TestCase
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

    public function can_get_specified_category()
    {
        Category::factory()->count(10)->create();
        $category = Category::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/general/categories/{$category->id}");

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_get_specified_publisher()
    {
        Publisher::factory()->count(10)->create();
        $publisher = Publisher::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/general/publishers/{$publisher->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    /** @test */
    public function can_get_specified_school()
    {
        School::factory()->count(10)->create();
        $school = School::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/general/schools/{$school->id}");
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_get_saves_with_specific_id()
    {
        Save::factory()->count(10)->create();
        $save = Save::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/general/saves/{$save->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['user_id'], $save->user_id);
        $this->assertEquals($array['data']['resource_id'], $save->resource_id);
    }
}