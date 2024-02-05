<?php

namespace Transave\ScolaBookstore\Tests\Feature\Book;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Resource::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }


    /** @test */
    function can_delete_book_with_specific_id()
    {
        $book = Resource::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/books/{$book->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);

    }

}