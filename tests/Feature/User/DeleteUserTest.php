<?php

namespace Transave\ScolaBookstore\Tests\Feature\User;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        User::factory(10)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }


    /** @test */
    function can_delete_user_successfully()
    {
        $user = User::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/users/{$user->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNull($arrayData['data']);
    }
}
