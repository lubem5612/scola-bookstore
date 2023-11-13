<?php

namespace Transave\ScolaBookstore\Tests\Feature\Restful;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateResourceTest extends TestCase
{
    private $user, $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create([
            'email' => 'sampledata@test.com',
            'password' => bcrypt('sample1234'),
        ]);
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function can_create_category()
    {
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->json('POST', '/bookstore/general/categories', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

    /** @test */
    public function can_create_publishers()
    {
        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->json('POST', 'bookstore/general/publishers', $data, ['Accept' => 'application/json']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

    /** @test */
    public function can_create_carts()
    {
        $book = Book::factory()->create();
        $data = [
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'quantity' => $this->faker->randomDigit(),
            'amount' => $this->faker->randomNumber(4, 9),
            'total_amount' => $this->faker->randomNumber(4, 9),
        ];

        $response = $this->json('POST', 'bookstore/general/carts', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }


    /** @test */
    public function can_create_schools()
    {
        $data = [
            'faculty' => $this->faker->word,
            'department' => $this->faker->word,
        ];

        $response = $this->json('POST', 'RR', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }


    /** @test */
    public function can_create_saves()
    {
        $book = Book::factory()->create();
        $data = [
            'user_id' => $this->user->id,
            'book_id' => $book->id,
        ];

        $response = $this->json('POST', 'bookstore/general/saves', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }


    /** @test */
    public function can_create_orderdetails()
    {
        $order = Order::factory()->create();
        $book = Book::factory()->create();
        $data = [
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => $this->faker->randomFloat(),
            'total_price' => $this->faker->randomFloat(),
            'discount' => $this->faker->randomFloat(),
        ];

        $response = $this->json('POST', 'bookstore/general/orderdetails', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }


}