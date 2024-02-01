<?php


namespace Transave\ScolaBookstore\Tests\Feature\Resources;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Pickup;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\State;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdatePickupTest extends TestCase
{
    private $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'admin']);
        Lg::factory()->count(10)->create();
        Country::factory()->count(10)->create();
        State::factory()->count(10)->create();
        Pickup::factory()->count(10)->create();
        Order::factory()->count(10)->create();
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_update_pickup_successfully()
    {
        $request = [
            'address' => $this->faker->address,
            'recipient_first_name' => $this->faker->name,
            'recipient_last_name' => $this->faker->name,
            'alternative_phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'order_id' => Order::factory()->create()->id,
            'country_id' => Country::factory()->create()->id,
            'state_id' => State::factory()->create()->id,
            'lg_id' => Lg::factory()->create()->id,
            'postal_code' => $this->faker->countryISOAlpha3
        ];
        $pickup = Pickup::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/pickups/$pickup->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

}