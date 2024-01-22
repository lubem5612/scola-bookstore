<?php
namespace Transave\ScolaBookstore\Tests\Feature\ResearchResource;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ResearchResource;
use Transave\ScolaBookstore\Tests\TestCase;

class GetResearchResourceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five resources records for testing.
        ResearchResource::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_research_resource_with_specific_id()
    {
        // Get a random researchResource from the database.
        $researchResource = ResearchResource::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the researchResource by its ID.
        $response = $this->json('GET', "bookstore/research_resources/{$researchResource->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the researchResource's properties.
        $this->assertEquals($researchResource->user_id, $data['data']['user_id']);
    }
}
