<?php

namespace Transave\ScolaBookstore\Tests\Feature\Monograph;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteMonographTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Monograph::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);


    /** @test */
    function can_delete_monograph_with_specific_id()
    {
        $monograph = Monograph::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/monographs/{$monograph->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }
  }
}