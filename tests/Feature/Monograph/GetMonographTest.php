<?php
namespace Transave\ScolaBookstore\Tests\Feature\Monograph;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Tests\TestCase;

class GetMonographTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        Monograph::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_monograph_with_specific_id()
    {
        // Get a random monograph from the database.
        $monograph = Monograph::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the monograph by its ID.
        $response = $this->json('GET', "bookstore/monographs/{$monograph->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the monograph's properties.
        $this->assertEquals($monograph->user_id, $data['data']['user_id']);
    }
}
