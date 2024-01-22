<?php
namespace Transave\ScolaBookstore\Tests\Feature\Journal;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Actions\Journals\GetJournal;
use Transave\ScolaBookstore\Tests\TestCase;

class GetJournalTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        Journal::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_journal_with_specific_id()
    {
        // Get a random journal from the database.
        $journal = Journal::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the journal by its ID.
        $response = $this->json('GET', "bookstore/journals/{$journal->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the journal's properties.
        $this->assertEquals($journal->user_id, $data['data']['user_id']);
    }
}
