<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\User;

class ConferencePaperFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConferencePaper::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'category_id' => Category::factory(),
            'conference_title'=> $this->faker->name,
            'conference_date' => $this->faker->date(),
            'primary_author' => $this->faker->name,
            'other_authors' => json_encode([$this->faker->name, $this->faker->name]),
            'location' => $this->faker->address,
            'file' => $this->fake->word,
            'abstract' => $this->faker->text,
            'introduction' => $this->faker->text,
            'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
            'background' => $this->faker->text,
            'methodology' => $this->faker->paragraph,
            'references' => json_encode([$this->faker->text]),
            'conclusion' => $this->faker->sentence,
            'result' => $this->faker->text,
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            'pages' => 100,

        ];
    }

}
