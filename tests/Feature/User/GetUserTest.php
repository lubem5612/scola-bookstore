<?php

namespace Transave\ScolaBookstore\Tests\Feature\User;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class GetUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        User::factory()->count(20)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }


    /** @test */
    function can_get_users_with_specific_id()
    {
        $user = User::query()->inRandomOrder()->first();
        $response = $this->json('GET', "bookstore/users/{$user->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);

    }

}