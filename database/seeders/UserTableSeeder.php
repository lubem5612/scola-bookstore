<?php

namespace Transave\ScolaBookstore\Database\Seeders;
use Carbon\Carbon;
use Transave\ScolaBookstore\Http\Models\User;

class UserTableSeeder
{
    public function run()
    {
        if (User::query()->count() == 0) {
            foreach ($this->users as $index => $user)
            {
                $user['email_verified_at'] = Carbon::now();
                $user['password'] = bcrypt('password');
                User::query()->create($user);
            }
        }
    }

    protected $users = [
        'super_admin' => [
            'first_name' => 'Super',
            'last_name' => 'Bookstore',
            'email' => 'superadmin@bookstore.com',
            'phone' => '+2340812345678',
            'is_verified' => 1,
            'role' => 'super_admin'
        ],
        'admin' => [
            'first_name' => 'Admin',
            'last_name' => 'Bookstore',
            'email' => 'admin@bookstore.com',
            'phone' => '+2347812345678',
            'is_verified' => 1,
            'role' => 'admin'
        ],
        'user' => [
            'first_name' => 'User',
            'last_name' => 'Bookstore',
            'email' => 'user@bookstore.com',
            'phone' => '+2349812345678',
            'is_verified' => 1,
            'role' => 'user'
        ],
        'reviewer' => [
            'first_name' => 'Reviewer',
            'last_name' => 'Bookstore',
            'email' => 'reviewer@bookstore.com',
            'phone' => '+2349112345678',
            'is_verified' => 1,
            'role' => 'reviewer'
        ],
    ];
}