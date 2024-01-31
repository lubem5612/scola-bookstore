<?php

namespace Transave\ScolaBookstore\Tests\Feature\Monograph;


use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Transave\ScolaBookstore\Actions\Monographs\CreateMonograph;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateMonographTest extends TestCase
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
    public function can_create_monograph_via_action()
    {
        $response = (new CreateMonograph($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }
    

    /** @test */
    function can_create_monograph_successfully()
    {
        $response = $this->json('POST', 'bookstore/monographs', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
                        dd($arrayData);
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
               'title' => $this->faker->name,
               'publication_date' => $this->faker->date(),
               'publication_year' => $this->faker->date(),
               'subtitle' => $this->faker->name,
               'abstract' => $this->faker->text,
               'content' => $this->faker->text,
               'primary_author' => $this->faker->name,
               'contributors' => json_encode([$this->faker->name, $this->faker->name]),
               'keywords' => json_encode([$this->faker->word, $this->faker->word]),
               'cover_image' => UploadedFile::fake()->image('cover.jpg'),
               'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
               'ISBN' => $this->faker->unique()->isbn13,
               'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
               'price' => $this->faker->randomNumber(2,9),
               'percentage_share' => 50,
                "faculty" => $this->faker->word,
               "department" => $this->faker->word,
               ];
    }


}