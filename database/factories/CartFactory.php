<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Cart;

class CartFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'book_id' => Book::factory(),
            'quantity' => $this->faker->randomDigit(),
            'amount' => $this->faker->randomNumber(4,9),
            'total_amount' => $this->faker->randomNumber(4,9),
        ];
    }

}