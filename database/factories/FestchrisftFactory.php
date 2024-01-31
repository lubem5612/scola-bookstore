<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Festchrisft;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class FestchrisftFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Festchrisft::class;

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
            'subtitle' => $this->faker->name,
            'abstract' => $this->faker->text,
            'content' => $this->faker->text, 
            'editors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'keywords'=>json_encode([$this->faker->word, $this->faker->word, $this->faker->word]), 
            'publication_date' => $this->faker->date(), 
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'introduction'=> $this->faker->sentence,
            'dedicatees'=>json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'price' => $this->faker->randomNumber(2,9),
            "faculty" => $this->faker->word,
            "department" => $this->faker->word,
            'percentage_share' => 50,
        ];
        
    }

}

