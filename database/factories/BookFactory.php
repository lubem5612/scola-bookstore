<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
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
            'publisher' => $this->faker->company,
            'publication_date' => $this->faker->date(),
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'preface' => $this->faker->sentence,
            'abstract' => $this->faker->text,
            'content' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'ISBN' => $this->faker->unique()->isbn13,
            'edition' => $this->faker->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Fourth Edition',]),
            'price' => $this->faker->randomNumber(2,9),
            'tags' => $this->faker->words(3, true),
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'summary' => $this->faker->paragraph,
            'percentage_share' => 50,

        ];
    }

}

