<?php

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Tests\TestCase;

class GetBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        Resource::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_book_with_specific_id()
    {
        // Get a random book from the database.
        $book = Resource::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the book by its ID.
        $response = $this->json('GET', "bookstore/books/{$book->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the book's properties.
        $this->assertEquals($book->user_id, $data['data']['user_id']);
        $this->assertEquals($book->category_id, $data['data']['category_id']);
        $this->assertEquals($book->publisher_id, $data['data']['publisher_id']);
    }
}
