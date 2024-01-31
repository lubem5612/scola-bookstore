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
        $response = $this->json('PATCH', "bookstore/books/{$book->id}", $this->request, ['Accept' => 'application/json']);
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }

    private function testData()
    {
       $this->faker = Factory::create();
        $this->request = [
            'book_id' => Book::factory()->create()->id,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'publisher_id' => Publisher::factory()->create()->id,
            'publisher' => $this->faker->company,
            'publication_date' => $this->faker->date(),
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'preface' => $this->faker->sentence,
            'content' => $this->faker->text,
            'abstract' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'ISBN' => $this->faker->unique()->isbn13,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
            'price' => $this->faker->randomNumber(2,9),
            'tags' => $this->faker->words(3, true),
            'summary' => $this->faker->paragraph,
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'percentage_share' => 50,
        ];
    }
}
