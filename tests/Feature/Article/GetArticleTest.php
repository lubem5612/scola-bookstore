<?php

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Tests\TestCase;

class GetArticleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Create five book records for testing.
        Article::factory()->count(5)->create();

        // Create a user with the role 'superAdmin' and authenticate them.
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function it_can_get_article_with_specific_id()
    {
        // Get a random article from the database.
  
        $article = Article::query()->inRandomOrder()->first();

        // Send a GET request to retrieve the article by its ID.
        $response = $this->json('GET', "bookstore/articles/{$article->id}", ['Accept' => 'application/json']);

        // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertNotNull($data['data']);
    }
}
