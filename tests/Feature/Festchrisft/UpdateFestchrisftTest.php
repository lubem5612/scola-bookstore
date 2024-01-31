<?php

namespace Transave\ScolaBookstore\Tests\Feature\Festchrisft;


use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Actions\Festchrisfts\UpdateFestchrisft;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateFestchrisftTest extends TestCase
{
    private $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->festchrisft = Festchrisft::factory()->create();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->testData(); 
    }


        /** @test */
    public function can_update_festchrisft_via_action()
    {
        $response = (new UpdateFestchrisft($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }
    

    /** @test */
    function can_update_festchrisft_successfully()
    {
        $response = $this->json('PATCH', "bookstore/festchrisfts/{$this->festchrisft->id}", $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);             
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);

    }



    private function testData()
    {    
         $this->faker = Factory::create(); 
        $this->request = [
            'festchrisft_id' => $this->festchrisft->id,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id, 
            'category_id' => Category::factory()->create()->id,
            'publisher_id' => Publisher::factory()->create()->id,
            'publisher' => $this->faker->company,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'abstract'=>$this->faker->text, 
            'content'=>$this->faker->text,  
            'editors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'keywords'=>json_encode([$this->faker->word, $this->faker->word, $this->faker->word]), 
            'publication_date' => $this->faker->date(), 
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'introduction'=> $this->faker->sentence,
            'dedicatees'=>json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
               ];
    }


}