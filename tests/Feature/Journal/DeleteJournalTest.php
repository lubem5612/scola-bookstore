<?php

namespace Transave\ScolaBookstore\Tests\Feature\Journal;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteJournalTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Journal::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);


    /** @test */
    function can_delete_journal_with_specific_id()
    {
        $journal = Journal::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/journals/{$journal->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }
  }
}