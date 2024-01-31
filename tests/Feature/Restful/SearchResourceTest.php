<?php

namespace Transave\ScolaBookstore\Tests\Feature\Restful;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\OrderItem;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class SearchResourceTest extends TestCase
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

    public function can_get_categories()
    {
        Category::factory()->count(10)->create();
        $response = $this->json('GET', "bookstore/general/categories");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_get_publishers()
    {
        Publisher::factory()->count(10)->create();
        $response = $this->json('GET', "bookstore/general/publishers");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    /** @test */
    public function can_get_schools()
    {
        School::factory()->count(10)->create();
        $response = $this->json('GET', "bookstore/general/schools");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_get_saves()
    {
        Save::factory()->count(10)->create();
        $response = $this->json('GET', "bookstore/general/saves");
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}