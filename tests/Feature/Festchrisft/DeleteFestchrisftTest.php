<?php

namespace Transave\ScolaBookstore\Tests\Feature\Festchrisft;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Tests\TestCase;

class DeleteFestchrisftTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Festchrisft::factory()->count(5)->create();
        $user = config('scola-bookstore.auth_model')::factory()->create(['role' => 'superAdmin']);
        Sanctum::actingAs($user);
    }


    /** @test */
    function can_delete_festchrisft_with_specific_id()
    {
        $festchrisft = Festchrisft::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "bookstore/festchrisfts/{$festchrisft->id}");
        $array = json_decode($response->getContent(), true);
        dd($array);
        $this->assertEquals(true, $array['success']);

    }

}