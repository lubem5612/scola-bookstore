<?php

namespace Transave\ScolaBookstore\Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Transave\ScolaBookstore\Actions\Auth\ChangePassword;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->request = [
            'password' => $this->faker->password(8),
        ];
    }

    /** @test */
    public function can_change_password()
    {
        $action = new ChangePassword($this->user, $this->request);
        $response = $action->execute();

        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Password changed successfully', $response->getData()->message);
        $this->assertEquals($this->user->id, $response->getData()->data->id);
    }
}
