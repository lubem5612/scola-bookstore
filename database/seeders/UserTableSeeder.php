<?php


use Transave\ScolaBookstore\Http\Models\User;

class UserTableSeeder
{
    public function run()
    {
        if (User::query()->count() == 0) {
            foreach (config('constants.users') as $index => $user)
            {
                User::query()->create($user);
            }
        }
    }
}