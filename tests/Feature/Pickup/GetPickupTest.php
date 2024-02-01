<?php

namespace Transave\ScolaBookstore\Tests\Feature\Resources;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Pickup;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class GetPickupTest extends TestCase
{
    private $user, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create(['email' => 'sampledata@test.com', 'password' => bcrypt('sample1234'),]);
        Sanctum::actingAs($this->user);
    }


    /** @test */

    public function can_get_specified_pickup()
    {
        Pickup::factory()->count(10)->create();
        $pickup = Pickup::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/pickups/{$pickup->id}");

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}