<?php

namespace Transave\ScolaBookstore\Tests\Unit\Models;


use Transave\ScolaBookstore\Http\Models\Book;
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
        $this->assertTrue(class_exists(Book::class), 'Book model does not exist.');
    }
}