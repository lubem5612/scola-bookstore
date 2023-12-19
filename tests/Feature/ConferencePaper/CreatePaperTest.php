<?php

namespace Transave\ScolaBookstore\Tests\Feature\ConferencePaper;

use Faker\Factory;
// use Illuminate\Http\UploadedFile;
use Illuminate\Http\Testing\File;
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
        $file = File::image('file.jpg');
        $this->faker = Factory::create();
        $conferencePaper = ConferencePaper::factory()->create([
            'other_authors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
             'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
              'references' => json_encode([$this->faker->text]),
        ]);
        $this->request = [
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'conference_title'=> $this->faker->name,
            'conference_date' => $this->faker->date(),
            'category_id' => Category::factory()->create()->id,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'primary_author' => $this->faker->name,
            'file' => $file,
            'publisher' => $this->faker->name,
            'abstract' => $this->faker->text,
            'other_authors' => $conferencePaper->other_authors,
            'introduction' => $this->faker->text,
            'keywords' => $conferencePaper->keywords,
            'background' => $this->faker->text,
            'methodology' => $this->faker->paragraph,
            'references' => $conferencePaper->references,
            'conclusion' => $this->faker->sentence,
            'result' => $this->faker->text,
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            'location' => $this->faker->address,
            'pages' => "100",
        ];
    }
}
