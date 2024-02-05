<?php

namespace Transave\ScolaBookstore\Tests\Unit\Models;


use Transave\ScolaBookstore\Http\Models\Resource;
use Transave\ScolaBookstore\Tests\TestCase;

class BookModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_can_check_if_Book_model_exists()
    {
        $this->assertTrue(class_exists(Resource::class), 'Book model does not exist.');
    }
}