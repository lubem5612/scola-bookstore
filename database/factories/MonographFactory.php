<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Monograph;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;

class MonographFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Monograph::class;


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
            'publisher' => $this->faker->company,
            'title' => $this->faker->name,
            'publication_date' => $this->faker->date(),
            'publication_year' => $this->faker->date(),
            'subtitle' => $this->faker->name,
            'abstract' => $this->faker->sentence,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->word, $this->faker->word]),
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'ISBN' => $this->faker->unique()->isbn13,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
        ];
    }

}
