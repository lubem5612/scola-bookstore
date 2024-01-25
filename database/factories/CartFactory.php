<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'user_id' => config('scola-bookstore.auth_model')::factory()->create()->id,
            'resource_id' => $this->faker->uuid,
            'quantity' => $this->faker->numberBetween(1, 10),
            'resource_type' => $this->faker->randomElement(['Book', 'Report', 'Journal', 'Festchrisft', 'ConferencePaper', 'ResearchResource', 'Monograph', 'Article']),
            'unit_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }

}
