<?php


namespace Transave\ScolaBookstore\Tests\Feature\Resources;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Bank;
use Transave\ScolaBookstore\Http\Models\BankDetail;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\State;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateResourceTest extends TestCase
{
    private $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'admin']);
        Bank::factory()->count(10)->create();
        BankDetail::factory()->count(10)->create();
        Lg::factory()->count(10)->create();
        Country::factory()->count(10)->create();
        State::factory()->count(10)->create();
        Category::factory()->count(10)->create();
        School::factory()->count(10)->create();
        Publisher::factory()->count(10)->create();
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_update_category_successfully()
    {
        $request = [
            'name' => $this->faker->name,
        ];
        $category = Category::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/categories/$category->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


        /** @test */
    function can_update_publisher_successfully()
    {
        $request = [
            'name' => $this->faker->name,
        ];
        $publisher = Publisher::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/publishers/$publisher->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


        /** @test */
    function can_update_country_successfully()
    {
        $request = [
            'name' => $this->faker->country,
            'code' => $this->faker->countryISOAlpha3
        ];
        $country = Country::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/countries/$country->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


            /** @test */
    function can_update_state_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'capital' => $this->faker->name,
            'country_id' => Country::factory()->create()->id,
        ];
        $state = State::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/states/$state->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

                /** @test */
    function can_update_lg_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'state_id' => State::factory()->create()->id,
        ];
        $lg = Lg::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/lgs/$lg->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


        function can_update_bank_successfully()
    {
        $request = [
            'name' => $this->faker->name,
            'code' => "235674",
            'country_id' => Country::factory()->create()->id,
        ];
        $bank = Bank::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/banks/$bank->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    function can_update_bank_details_successfully()
    {
        $request = [
            'account_name' => "James A",
            'account_number' => 1234567819,
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'bank_id' => Bank::factory()->create()->id,
        ];
        $bank_detail = BankDetail::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/bank_details/$bank_detail->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


            function can_update_school_successfully()
    {
        $request = [
            'faculty' => $this->faker->word,
            'department' => $this->faker->word,
        ];

        $school = School::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "bookstore/general/schools/$school->id", $request);
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}