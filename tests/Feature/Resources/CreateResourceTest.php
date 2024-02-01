<?php


namespace Transave\ScolaBookstore\Tests\Feature\Resources;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\State;
use Transave\ScolaBookstore\Http\Models\Bank;
use Transave\ScolaBookstore\Tests\TestCase;

class CreateResourceTest extends TestCase
{
    private $request, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_create_category_successfully()
    {
        $request = ['name' => $this->faker->name];
        $response = $this->json('POST', "bookstore/general/categories", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_create_saves_successfully()
    {
        $request = [
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'resource_id' => $this->faker->uuid,
            'resource_type' => $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']),
        ];
        $response = $this->json('POST', "bookstore/general/saves", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_create_publisher_successfully()
    {
        $request = ['name' => $this->faker->name];
        $response = $this->json('POST', "bookstore/general/publishers", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_create_country_successfully()
    {
        $request = [
            'name' => $this->faker->country,
            'code' => $this->faker->countryISOAlpha3
        ];
        $response = $this->json('POST', "bookstore/general/countries", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_create_state_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'capital' => $this->faker->name,
            'country_id' => Country::factory()->create()->id,
        ];
        $response = $this->json('POST', "bookstore/general/states", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


        /** @test */
    function can_create_lg_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'state_id' => State::factory()->create()->id,
        ];
        $response = $this->json('POST', "bookstore/general/lgs", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_create_bank_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'code' => "235674",
            'country_id' => Country::factory()->create()->id,
        ];
        $response = $this->json('POST', "bookstore/general/banks", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    
    /** @test */
    function can_create_bank_details_successfully()
    {
        $request = [
            'account_name' => $this->faker->name,
            'account_number' => 1234567819,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'bank_id' => Bank::factory()->create()->id,
        ];
        $response = $this->json('POST', "bookstore/general/bank_details", $request,  ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


        /** @test */
    public function can_create_schools()
    {
        $request = [
            'faculty' => $this->faker->word,
            'department' => $this->faker->word,
        ];

        $response = $this->json('POST', 'bookstore/general/schools', $request, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

}