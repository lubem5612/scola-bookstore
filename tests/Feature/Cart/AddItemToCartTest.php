<?php

namespace Transave\ScolaBookstore\Tests\Feature\Journal;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Actions\CartActions\AddToCart;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;


class AddItemToCartTest extends TestCase
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
    public function can_add_to_cart_via_action()
    {
        $response = (new AddToCart($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        dd($array);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }
    

    /** @test */
    function can_add_to_cart_via_api()
    {
        $response = $this->json('POST', 'bookstore/carts', $this->request, ['Accept' => 'application/json']);
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
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,  
            'content'=>$this->faker->text, 
            'abstract'=>$this->faker->text, 
            'editors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),
            'publication_year' => $this->faker->date(), 
            'volume' => "100",
            'page_start' => "50",
            'page_end' => "100",
            'editorial' => $this->faker->sentence,
            'editorial_board_members' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),   
            'website' => $this->faker->text, 
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            'conclusion' => $this->faker->sentence,
               ];
    }


}