<?php

namespace Models;

use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Tests\TestCase;

class OrderModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_can_check_if_Order_model_exists()
    {
        $this->assertTrue(class_exists(Order::class), 'Order model does not exist.');
    }
}