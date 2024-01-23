<?php

namespace Transave\ScolaBookstore\Tests\Feature\ResearchResource;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\ResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\CreateResearchResource;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateResearchResourceTest extends TestCase
{
    private $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }


        /** @test */
    public function can_create_research_resource_via_action()
    {
        $response = (new CreateResearchResource($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }
    

    /** @test */
    function can_create_research_resource_successfully()
    {
        $response = $this->json('POST', 'bookstore/research_resources', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);

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
               'publication_year' => $this->faker->date(),
               'source' => $this->faker->name,
               'abstract' => $this->faker->text,
               'content' => $this->faker->text,
               'resource_url' => $this->faker->name,
               'title' => $this->faker->name,
               'subtitle' => $this->faker->name, 
               'overview' => $this->faker->sentence,
               'resource_type'=> $this->faker->name,
               'primary_author' => $this->faker->name,          
               'contributors' => json_encode([$this->faker->name, $this->faker->name]),
               'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
               'cover_image' => UploadedFile::fake()->image('cover.jpg'),
               'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
               'percentage_share' => 50,          
               'price' => $this->faker->randomNumber(2,9),
        ]; 
    }


}