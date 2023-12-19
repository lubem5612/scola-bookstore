<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Articles\CreateArticle;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateArticleTest extends TestCase
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
    public function can_create_article_via_action()
    {
        $response = (new CreateArticle($this->request))->execute();
                dd($response);
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
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
       $file = UploadedFile::fake()->image('file.jpg');
        $this->faker = Factory::create();
        $article = Article::factory()->create([
            'other_authors' => json_encode([$this->faker->name, $this->faker->name]),
             'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
             'references' => json_encode([$this->faker->text]),
        ]);
        $this->request = [
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
            'ISSN' => "JHKI972",
            'percentage_share' => 50,
            'discussion'=> $this->faker->sentence,
            'literature_review'=> $this->faker->text,
            'pages' => '20',
        ];
    }
}
