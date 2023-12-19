<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Article;
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
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'abstract' => $this->faker->text,
            'primary_author' => $this->faker->name,
            'other_authors' => json_encode([$this->faker->name, $this->faker->name]),
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'introduction' => $this->faker->text,
            'file' => $this->fake->word,
            'publish_date' => $this->faker->date(),
            'literature_review' => $this->faker->sentence,            
            'discussion' => $this->faker->text,
            'methodology' => $this->faker->paragraph,
            'references' => json_encode([$this->faker->text]),
            'conclusion' => $this->faker->sentence,
            'result' => $this->faker->text,
            'price' => $this->faker->randomNumber(2,9),
            'ISSN' => "JHKI972",
            'percentage_share' => 50,
            'pages' => 20,
        ];

        
    }

}

