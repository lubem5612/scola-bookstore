<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Transave\ScolaBookstore\Http\Models\School;
use Transave\ScolaBookstore\Http\Models\User;

class UserFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'is_verified' => $this->faker->randomElement([0, 1]),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['superAdmin', 'admin', 'publisher', 'user']),
            'user_type' => $this->faker->randomElement(['reviewer', 'normal']),
            'school_id' => School::factory(),
            "bio" => $this->faker->text,
            "specialization" => $this->faker->randomElement(['Math', 'Physics', 'Chemistry', 'Biology', 'Computer Science']),
            "address" => $this->faker->address,
            'profile_image' => $this->faker->image,
            'phone' => $this->faker->phoneNumber,
            "delivery_address" => $this->faker->address,

        ];
    }


}