<?php
namespace Transave\ScolaBookstore\Tests\Feature\Festchrisft;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Tests\TestCase;

class GetFestchrisftTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        Festchrisft::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_a_festchrisft_with_specific_id()
    {
        // Get a random festchrisft from the database.
        $festchrisft = Festchrisft::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the festchrisft by its ID.
        $response = $this->json('GET', "bookstore/festchrisfts/{$festchrisft->id}");

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);

        // Assert that specific fields in the response match the festchrisft's properties.
        $this->assertEquals($festchrisft->user_id, $data['data']['user_id']);
    }
}
