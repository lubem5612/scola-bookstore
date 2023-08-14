<?php

namespace Transave\ScolaBookstore\Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Transave\ScolaBookstore\Actions\Auth\ChangeEmail;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class ChangeEmailTest extends TestCase
{
    use RefreshDatabase; 

    private $user;
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->testData();
    }

    /** @test */
    public function can_change_email()
    {
        $response = (new ChangeEmail($this->user, $this->request))->execute();
        $responseData = $response->getData();

        $this->assertTrue($responseData->success);
        $this->assertNotNull($responseData->data);
    }

    private function testData()
    {
        $this->request = [
            'email' => 'testmail@example.com',
        ];
    }
}