<?php

namespace Transave\ScolaBookstore\Tests\Feature\ResearchResource;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ResourceCategory;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteResearchResourceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        ResourceCategory::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);


    /** @test */
    function can_delete_research_resource_with_specific_id()
    {
        $researchResource = ResourceCategory::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/research_resources/{$researchResource->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }
  }
}