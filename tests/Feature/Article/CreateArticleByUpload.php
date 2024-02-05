<?php

namespace Transave\ScolaBookstore\Tests\Feature\Article;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Articles\CreateArticle;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Article;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateArticleByUpload extends TestCase
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
        $this->faker = Factory::create();
        $this->request = [
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'category_id' => Category::factory()->create()->id,
            'publisher_id' => Author::factory()->create()->id,
            'publisher' => $this->faker->company,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),       
            'price' => $this->faker->randomNumber(2,9),
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'percentage_share' => 50,
            'pages' => "20",
        ];
    }
}
