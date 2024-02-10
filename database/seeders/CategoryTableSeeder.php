<?php

namespace Transave\ScolaBookstore\Database\Seeders;

use Transave\ScolaBookstore\Http\Models\Category;

class CategoryTableSeeder
{

    public function run()
    {
        $categories = config('constants.categories');
        foreach ($categories as $category) {
            Category::query()->updateOrCreate([
                'name' => $category
            ], [
                'name' => $category
            ]);
        }
    }
}