<?php

namespace Transave\ScolaBookstore\Tests\Feature\Book;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Book\UpdateBook;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateBookTest extends TestCase
{
    private $user;
    private $book;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->book = Book::factory()->create();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */
    public function test_can_update_book_via_action()
    {
        $response = (new UpdateBook($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    /** @test */
    public function test_can_update_book_via_api()
    {
        $book = Book::query()->inRandomOrder()->first();
        $response = $this->json('PUT', "bookstore/books/{$book->id}", $this->request, ['Accept' => 'application/json']);
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }

    private function testData()
    {
        $file = UploadedFile::fake()->image('file.jpg');
        $this->faker = Factory::create();
        $cover = UploadedFile::fake()->image('cover.png');
        $book = Book::factory()->create([
            'other_authors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
        ]);
        $this->request = [
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'book_id' => Book::factory()->create()->id,
            'publisher_id' => Publisher::factory()->create()->id,
            'introduction' => $this->faker->name,
            'abstract' => $this->faker->sentence,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'primary_author' => $this->faker->name,
            'table_of_contents' => $this->faker->paragraph,
            'cover' => $cover,
            'file' => $file,
            'publish_date' => $this->faker->date(),
            'other_authors' => $book->other_authors,
            'publisher' => $this->faker->company,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition']),
            'ISBN' => $this->faker->unique()->isbn13,
            'price' => $this->faker->randomNumber(2, 9),
            'tags' => $this->faker->words(3, true),
            'summary' => $this->faker->paragraph,
            'percentage_share' => 50,
            'language' => $this->faker->name,
        ];
    }
}
