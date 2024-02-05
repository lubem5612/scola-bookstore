<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Department;
use Transave\ScolaBookstore\Http\Models\Faculty;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-bookstore.auth_model')::factory(),
            'department_id' => Department::factory(),
            'faculty_id' => Faculty::factory(),
            'specialization' => $this->faker->sentence,
            'bio' => $this->faker->sentence(40, true),
            'bank_info' => json_encode(['account_no' => $this->faker->randomDigit(), 'account_name' => $this->faker->name, 'bank_code' => $this->faker->hexColor]),
        ];
    }

}