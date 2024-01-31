<?php

namespace Transave\ScolaBookstore\Tests\Feature\Restful;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateResourceTest extends TestCase
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

    function can_update_specified_session()
    {
        $data = [
            'name' => $this->faker->name,
        ];
        Category::factory()->count(10)->create();
        $category = Category::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/bookstore/general/categories/{$category->id}", $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    /** @test */
    function can_update_specified_publisher()
    {
        $data = [
            'name' => $this->faker->name
        ];
        Publisher::factory()->count(10)->create();
        $publisher = Publisher::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/bookstore/general/publishers/{$publisher->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_school()
    {
        $school = School::factory()->create();
        $data = [
            'school_id' => $school->id,
            'faculty' => $this->faker->word,
            'department' => $this->faker->word,
        ];
        School::factory()->count(10)->create();
        $school = School::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/bookstore/general/schools/{$school->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_save()
    {
        $book = Book::factory()->create();
        $save = Save::factory()->create();
        $data = [
            'save_id' => $save->id,
            'user_id' => $this->user->id,
            'resource_id' => $this->faker->uuid,
            'resource_type' => $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']),
        ];

        Save::factory()->count(10)->create();
        $save = Save::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/bookstore/general/saves/{$save->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}