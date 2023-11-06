<?php

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
        dd($response);
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertNotNull($data['data']);

        $this->assertEquals($book->user_id, $data['data']['user_id']);
        $this->assertEquals($book->category_id, $data['data']['category_id']);
        $this->assertEquals($book->publisher_id, $data['data']['publisher_id']);
    }
}
