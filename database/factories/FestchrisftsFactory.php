<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Festchrisfts;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\User;

class FestchrisftsFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Festchrisfts::class;

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
            'publisher' => $this->faker->name,
            'title' => $this->faker->name,
            'subtitle' => $this->faker->name,
            'abstract'=>$this->faker->sentence,  
            'editors' => json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'keywords'=>json_encode([$this->faker->word, $this->faker->word, $this->faker->word]), 
            'publish_date' => $this->faker->date(), 
            'table_of_contents' => $this->faker->text,
            'cover' => $this->fake->image, 
            'file' => $this->fake->word,
            'introduction'=> $this->faker->sentence,
            'dedicatees'=>json_encode([$this->faker->name, $this->faker->name, $this->faker->name]),
            'references'=>json_encode([$this->faker->sentence, $this->faker->sentence, $this->faker->sentence]), 
            'price' => $this->faker->randomNumber(2,9),
            'percentage_share' => 50,
        ];
        
    }

}

