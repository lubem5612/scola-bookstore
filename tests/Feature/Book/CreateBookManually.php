<?php

namespace Transave\ScolaBookstore\Tests\Feature\Book;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
// use Illuminate\Http\Testing\File;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Book\CreateBook;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateBookManually extends TestCase
{
    private $user;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */
    public function can_create_book_via_action()
    {
        $response = (new CreateBook($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    /** @test */
    public function can_create_book_via_api()
    {
        $response = $this->json('POST', 'bookstore/books', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $array = json_decode($response->getContent(), true);
        dd($array);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }


    private function testData()
    {
        $this->faker = Factory::create();
        $this->request = [
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
            // 'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'ISBN' => $this->faker->unique()->isbn13,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
            'price' => $this->faker->randomNumber(2,9),
            'tags' => $this->faker->words(3, true),
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'summary' => $this->faker->paragraph,
            'percentage_share' => 50,
        ];
    }
}
