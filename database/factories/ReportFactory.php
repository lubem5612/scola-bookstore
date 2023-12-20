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
               'organization' => $this->faker->company,
               'institutional_affiliations'=> json_encode([$this->faker->company, $this->faker->company]),
               'primary_author' => $this->faker->name,          
               'abstract' => $this->faker->text,
               'contributors' => json_encode([$this->faker->name, $this->faker->name]),
               'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
               'summary'=> $this->faker->sentence,
               'file_path' => $this->fake->word,
               'cover_image'=> $this->faker->image,
               'funding_information'=> $this->faker->sentence,
               'license_information'=> $this->faker->sentence,
               'percentage_share' => 50,          
               'price' => $this->faker->randomNumber(2,9),
        ];
        
    }

}

