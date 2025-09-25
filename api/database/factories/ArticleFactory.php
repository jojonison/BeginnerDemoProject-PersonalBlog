<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'post' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'category_id' => Category::factory(),
            'user_id' => null,
        ];
    }

    public function withTags(): static
    {
        return $this->afterCreating(function (Article $article) {
            $article->tags()->attach(Tag::factory()->count(3)->create());
        });
    }
}
