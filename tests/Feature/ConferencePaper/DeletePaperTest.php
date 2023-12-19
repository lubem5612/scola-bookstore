<?php

namespace Transave\ScolaBookstore\Tests\Feature\ConferencePaper;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Tests\TestCase;

class DeletePaperTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        ConferencePaper::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);


    /** @test */
    function can_delete_paper_with_specific_id()
    {
        $conferencePaper = ConferencePaper::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/papers/{$conferencePaper->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }
  }
}