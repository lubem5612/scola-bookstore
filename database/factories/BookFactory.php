<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Book;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;

class BookFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'category_id' => Category::factory(),
            'publisher_id' => Publisher::factory(),
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'author' => $this->faker->name,
            'cover' => $this->faker->image,
            'file' => $this->faker->image,
            'publish_date' => $this->faker->date(),
            'publisher' => $this->faker->company,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
            'ISBN' => $this->faker->unique()->isbn13,
            'price' => $this->faker->randomNumber(2,9),
            'tags' => $this->faker->words(3, true),
            'summary' => $this->faker->paragraph,
            'percentage_share' => 50,

        ];
    }

}