<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Articles\CreateArticle;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateArticleManually extends TestCase
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
    public function can_create_article_via_api()
    {
        $response = $this->json('POST', 'bookstore/articles', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
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
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'abstract' => $this->faker->text,
            'content' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            // 'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),       
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            'pages' => "20",
        ];
    }
}
