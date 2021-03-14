<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => Str::ucfirst($this->faker->words(5, true)),
//            'banner' => $this->faker->image('public/storage/posts/banner',400,480, null, false),
            'body' => $this->faker->paragraph(20),
            'published' => $this->faker->boolean,
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
        ];
    }
}
