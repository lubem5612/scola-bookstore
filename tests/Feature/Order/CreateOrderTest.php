<?php

namespace Transave\ScolaBookstore\Tests\Feature\Order;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\Order\CreateOrder;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateOrderTest extends TestCase
{
    private $user;
    private $request;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'user']);
        Sanctum::actingAs($this->user);
        $this->request = $this->testData();
    }

    /** @test */
    public function can_create_order_via_action()
    {
        $response = (new CreateOrder($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertTrue($array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_create_order_via_api()
    {
        $response = $this->json('POST', 'bookstore/orders', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }


    private function testData()
    {
        $this->faker = Factory::create();
        $book = Book::factory()->create();

        return [
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'amount' => $this->faker->randomNumber(4, 9),
            'total_amount' => $this->faker->randomNumber(4, 9),
            'invoice_no' => $this->faker->unique()->randomDigit(),
            'status' => $this->faker->randomElement(['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled']),

        ];
    }
}