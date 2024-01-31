<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Article;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class ArticleFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

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
            'publisher_id'=> Publisher::factory(),
            'publisher' => $this->faker->company,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'abstract' => $this->faker->text,
            'content' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'contributors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),       
            'price' => $this->faker->randomNumber(2,9),
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'percentage_share' => 50,
            'pages' => 20,
        ];
        
    }

}

