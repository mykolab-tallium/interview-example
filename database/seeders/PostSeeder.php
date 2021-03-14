<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = User::factory()->count(1000)->create();
        $categories = Category::factory()->count(1000)->create();

//        $categories = Category::all();
//        $authors = User::all();

        Post::factory()
            ->count(50000)
            ->state(new Sequence(
                fn () => [
                    'category_id' => $categories->random()->id,
                    'author_id' => $authors->random()->id,
                ]
            ))
            ->create();
    }
}
