<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Faker\Factory;
// use Illuminate\Http\UploadedFile;
use Illuminate\Http\Testing\File;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Articles\UpdateArticle;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\Category;
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
     $file = File::image('file.jpg');
        $this->faker = Factory::create();
        $article = Article::factory()->create([
            'other_authors' => json_encode([$this->faker->name, $this->faker->name]),
             'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
             'references' => json_encode([$this->faker->paragraph, $this->faker->paragraph, $this->faker->paragraph]),
        ]);
        $this->request = [
            'article_id' => $this->article->id,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'category_id' => Category::factory()->create()->id,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'primary_author' => $this->faker->name,
            'file' => $file,
            'publish_date' => $this->faker->date(),
            'abstract' => $this->faker->text,
            'other_authors' => $article->other_authors,
            'introduction' => $this->faker->text,
            'keywords' => $article->keywords,
            'background' => $this->faker->text,
            'methodology' => $this->faker->paragraph,
            'references' => $article->references,
            'conclusion' => $this->faker->sentence,
            'result' => $this->faker->text,
            'price' => $this->faker->randomNumber(2,9),
            'ISSN' => "LKJHFH89H",
            'percentage_share' => 50,
            'discussion'=> $this->faker->sentence,
            'literature_review'=> $this->faker->text,
            'pages' => "20",
        ];
    }
}
