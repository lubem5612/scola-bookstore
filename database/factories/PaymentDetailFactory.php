<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\PaymentDetail;

class PaymentDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentDetail::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'account_number' => rand(1000000000, 9999999999),
            'account_name' => $this->faker->name,
            'account_status' => $this->faker->randomElement(['active', 'inactive']),
            'bank_name' => $this->faker->name,
            'bank_code' => rand(100, 999),
            'is_default' => rand(0, 1),
        ];
    }
}