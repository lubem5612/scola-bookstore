<?php


namespace Transave\ScolaBookstore\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaBookstore\Http\Models\Department;
use Transave\ScolaBookstore\Http\Models\Faculty;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'faculty_id' => Faculty::factory(),
        ];
    }
}