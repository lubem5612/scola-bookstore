<?php

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Tests\TestCase;

class GetPaperTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        ConferencePaper::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_paper_with_specific_id()
    {
        // Get a random Paper from the database.
        $conferencePaper = ConferencePaper::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the paper by its ID.
        $response = $this->json('GET', "bookstore/papers/{$conferencePaper->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the book's properties.
        $this->assertEquals($conferencePaper->user_id, $data['data']['user_id']);
    }
}
