<?php

namespace Transave\ScolaBookstore\Tests\Feature\User;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Actions\User\BecomeReviewer;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaBookstore\Tests\TestCase;

class BecomeReviewerTest extends TestCase
{
    private $user, $request, $faker;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-bookstore.auth_model')::factory()->create();
        $this->user = User::factory()->create(['id' => $this->user->id, 'user_type' => 'reviewer']);
        Sanctum::actingAs($this->user);
        $this->testData();

    }

        /** @test */
    public function can_become_reviewer_via_action()
    {       
        $response = (new BecomeReviewer($this->request))->execute();        
        $arrayData = json_decode($response->getContent(), true);

        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
    

        /** @test */
    public function can_become_reviewer_via_api()
    {
        $response = $this->json('PATCH', "bookstore/users/user-type/{$this->user->id}", $this->request, ['Accept' => 'application/json']);

               // Ensure the response status code is 200 (OK).
        $response->assertStatus(200);

        // Decode the JSON response.
        $data = json_decode($response->getContent(), true);

        // Assert that the response indicates success.
        $this->assertTrue($data['success']);

        // Assert that the 'data' field is not null.
        $this->assertNotNull($data['data']);
    }



    private function testData()
    {
        $this->request = [
            'user_id' => $this->user->id,
            'user_type' => $this->user->user_type,
        ];
    }
}