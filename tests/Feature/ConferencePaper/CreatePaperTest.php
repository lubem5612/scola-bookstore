<?php

namespace Transave\ScolaBookstore\Tests\Feature\ConferencePaper;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\ConferencePaper\CreateConferencePaper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Tests\TestCase;

class CreatePaperTest extends TestCase
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
    public function can_create_paper_via_action()
    {
        $response = (new CreateConferencePaper($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }


    /** @test */
    public function can_create_paper_via_api()
    {
        $response = $this->json('POST', 'bookstore/papers', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }


    private function testData()
    {
        $this->faker = Factory::create();
        $this->request = [
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'conference_name'=> $this->faker->name,
            'conference_date' => $this->faker->date(),
            'conference_year' => $this->faker->date(),
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'conference_location' => $this->faker->address,
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'content' => $this->faker->text,
            'abstract' => $this->faker->text,
            'institutional_affiliations' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'price' => $this->faker->randomNumber(2,9),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'percentage_share' => 50,
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
        ];
    }
}
