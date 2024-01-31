<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\ResearchResource;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class ResearchResourceFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResearchResource::class;

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
               'publication_year' => $this->faker->date(),
               'source' => $this->faker->name,
               'abstract' => $this->faker->text,
               'content' => $this->faker->text,
               'resource_url' => $this->faker->name,
               'price' => $this->faker->randomNumber(2,9),
               'overview' => $this->faker->sentence,
               'resource_type'=> $this->faker->name,
               'primary_author' => $this->faker->name,          
               'contributors' => json_encode([$this->faker->name, $this->faker->name]),
               'keywords' => json_encode([$this->faker->words, $this->faker->words, $this->faker->words]),
               'cover_image' => UploadedFile::fake()->image('cover.jpg'),
               'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
               'percentage_share' => 50,
                "faculty" => $this->faker->word,
               "department" => $this->faker->word,
               'price' => $this->faker->randomNumber(2,9),
        ];
    }

}

