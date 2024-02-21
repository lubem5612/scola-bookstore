<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Transaction;
use Transave\ScolaBookstore\Http\Models\User;

class TransactionFactory  extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'reference' => Carbon::now()->format('YmdHi').rand(10000, 99999),
            'amount' => $this->faker->randomFloat(2, 20000, 25000),
            'charges' => $this->faker->randomFloat(2, 2000, 2500),
            'commission' => $this->faker->randomFloat(2, 2000, 2500),
            'type' => $this->faker->randomElement(['debit', 'credit']),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'completed', 'on_the_way', 'cancelled']),
            'payload' => json_encode(['user' => 'test user']),
        ];
    }
}