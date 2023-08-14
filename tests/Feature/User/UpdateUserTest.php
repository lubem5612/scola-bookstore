<?php

namespace Transave\ScolaBookstore\Tests\Feature\User;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Actions\User\UpdateUser;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Tests\TestCase;

class UpdateUserTest extends TestCase
{
    private $user, $request, $faker;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create();
    }

    /** @test */
    public function can_update_user()
    {
        $this->testData();
        $response = (new UpdateUser($this->request))->execute();
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    private function testData()
    {
        $this->faker = Factory::create();
        $this->request = [
            'user_id' => $this->user->id,
            "first_name" => $this->faker->firstName,
            "last_name" => $this->faker->lastName,
            'school_id' => School::factory()->create()->id,
            "bio" => $this->faker->text,
            "specialization" => $this->faker->word,
            'faculty' => $this->faker->name,
            'department' => $this->faker->name,
            "address" => $this->faker->address,
            "delivery_address" => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'profile_image' => UploadedFile::fake()->image('pic.jpg'),
        ];
    }
}