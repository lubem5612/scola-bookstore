<?php

namespace Transave\ScolaBookstore\Tests\Unit\Models;

use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Tests\TestCase;

class CartModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_can_check_if_cart_model_exists()
    {
        $this->assertTrue(class_exists(Cart::class), 'cart model does not exist.');
    }

}