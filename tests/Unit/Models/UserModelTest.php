<?php

namespace Transave\ScolaBookstore\Tests\Unit\Models;



use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class UserModelTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_model_can_be_instantiated_with_factory()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }
}
