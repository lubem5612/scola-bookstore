<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Articles\UpdateArticle;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    private $user;
    private $article;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->article = Article::factory()->create();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */
    public function test_can_update_article_via_action()
    {
        $response = (new UpdateArticle($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }



    /** @test */
    public function test_can_update_article_via_api()
    {
        $article = Article::query()->inRandomOrder()->first();
        $response = $this->json('PUT', "bookstore/articles/{$article->id}", $this->request, ['Accept' => 'application/json']);
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }

    private function testData()
    {
        $this->faker = Factory::create();
        $this->request = [
            'article_id' => $this->article->id,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'category_id' => Category::factory()->create()->id,
            'publisher_id' => Publisher::factory()->create()->id,
            'publisher' => $this->faker->company,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            // 'abstract' => $this->faker->text,
            // 'content' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),       
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'pages' => "20",
        ];
    }
}
