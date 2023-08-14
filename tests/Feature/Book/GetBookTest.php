<?php

namespace Transave\ScolaBookstore\Tests\Feature\Book;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Tests\TestCase;

class GetBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Book::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_get_book_with_specific_id()
    {
        $book = Book::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/books/{$book->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['user_id'], $book->user_id);
        $this->assertEquals($array['data']['category_id'], $book->category_id);
        $this->assertEquals($array['data']['publisher_id'], $book->publisher_id);
    }
}