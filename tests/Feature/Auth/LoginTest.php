<?php

namespace Transave\ScolaBookstore\Tests\Feature\Auth;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class LoginTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'sampledata@test.com',
            'password' => bcrypt('sample1234'),
        ]);
    }


    /** @test */
    public function can_login_user_successfully()
    {
        $loginData = ['email' => 'sampledata@test.com', 'password' => 'sample1234'];
        Sanctum::actingAs($this->user);

        $response = $this->json('POST', route('bookstore.login'), $loginData, ['Accept' => 'application/json']);
        $response->assertStatus(200)->assertJsonStructure(["success", "message", "data"]);

        $this->assertAuthenticated();
    }

}