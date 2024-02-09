<?php


class DatabaseSeeder
{
    public function definition()
    {
        return [
            'countries' => CountryTableSeeder::class,
            'states' => StateTableSeeder::class,
            'users' => UserTableSeeder::class,
            'categories' => CategoryTableSeeder::class,
        ];
    }
}