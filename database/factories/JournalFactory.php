<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Journal;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class JournalFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Journal::class;

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
            'editors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'file_path' => UploadedFile::fake()->create('file.pdf', '500', 'application/pdf'),
            'publication_date' => $this->faker->date(),
            'publication_year' => $this->faker->date(), 
            'volume' => $this->faker->randomNumber(),
            'page_start' => $this->faker->randomNumber(),
            'page_end' => $this->faker->randomNumber(),
            'editorial' => $this->faker->sentence,
            'editorial_board_members' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),   
            'website' => $this->faker->text, 
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
            'conclusion' => $this->faker->sentence,
        ];
        
    }

}

