<?php

namespace Transave\ScolaBookstore\Tests\Feature\Auth;

use Faker\Factory;
use Transave\ScolaBookstore\Tests\TestCase;
use Transave\ScolaBookstore\Actions\Auth\Register;


class RegisterTest extends TestCase
{
    private $request, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->getTestData();
    }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_can_register_user()
    {
        $response = (new Register($this->request))->execute();
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }


    /**
     * Test successful user or publisher account registration.
     *
     * @return void
     */
    public function can_register_account_successfully()
    {
        $response = $this->json('POST', route('bookstore.register'), $this->request, ['Accept' => 'application/json']);
        dd($response);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $json = $response->json();
        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    private function getTestData()
    {
        $this->request = [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => $this->faker->randomElement(config('scola-bookstore.role')),
            'password' => 'password1234',
        ];
    }
}
