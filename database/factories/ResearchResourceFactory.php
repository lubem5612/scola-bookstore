<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Report;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class ReportFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

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
               'publisher_id'=> Publisher::factory(),
               'publisher' => $this->faker->company,
               'publication_date' => $this->faker->date(),
               'overview' => $this->faker->sentence,
               'resource_type'=> $this->faker->name,
               'primary_author' => $this->faker->name,          
               'contributors' => json_encode([$this->faker->name, $this->faker->name]),
               'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
               'file_path' => $this->fake->word,
               'cover_image'=> $this->faker->image,
               'percentage_share' => 50,          
               'price' => $this->faker->randomNumber(2,9),
        ];
    }

}

