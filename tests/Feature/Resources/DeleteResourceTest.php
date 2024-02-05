<?php

namespace Transave\ScolaBookstore\Tests\Feature\Resources;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Bank;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\bankDetail;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\State;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteResourceTest extends TestCase
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

    public function can_delete_specified_category()
    {
        Category::factory()->count(10)->create();
        $category = Category::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/categories/{$category->id}");

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_publisher()
    {
        Author::factory()->count(10)->create();
        $publisher = Author::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/publishers/{$publisher->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }



    /** @test */
    function can_delete_school_with_specific_id()
    {
        School::factory()->count(10)->create();
        $school = School::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/schools/{$school->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }

    /** @test */
    function can_delete_saves_with_specific_id()
    {
        Save::factory()->count(10)->create();
        $save = Save::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/saves/{$save->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }


        /** @test */
    function can_delete_country_with_specific_id()
    {
        Country::factory()->count(10)->create();
        $country = Country::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/countries/{$country->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }


        /** @test */
    function can_delete_state_with_specific_id()
    {
        State::factory()->count(10)->create();
        $state = State::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/states/{$state->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }

        /** @test */
    function can_delete_lg_with_specific_id()
    {
        Lg::factory()->count(10)->create();
        $lg = Lg::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/lgs/{$lg->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }

        /** @test */
    function can_delete_bank_with_specific_id()
    {
        Bank::factory()->count(10)->create();
        $bank = Bank::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/banks/{$bank->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }

        /** @test */
    function can_delete_bank_detail_with_specific_id()
    {
        BankDetail::factory()->count(10)->create();
        $bankDetail = BankDetail::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/general/bank_details/{$bankDetail->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
    }
}