<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\BankDetail;
use Transave\ScolaBookstore\Http\Models\Bank;


class BankDetailFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankDetail::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'bank_id' => Bank::factory(),
            'account_number' => $this->faker->unique()->randomNumber(9),
            'account_name' => $this->faker->optional()->firstName,
        ];
    }

}