<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'name_en' => Str::title($name),
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['necklace', 'earrings', 'bracelet', 'ring', 'anklet']),
            'tagline' => fake()->sentence(4),
            'tagline_en' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'description_en' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 400, 2000),
            'compare_at_price' => null,
            'image_path' => 'images/products/gun-dogumu-kolye.jpg',
            'material' => '18 ayar altın kaplama',
            'material_en' => '18k gold plated',
            'stone' => fake()->randomElement(['Sitrin', 'Amber', 'Pembe kuvars', 'Mine']),
            'stone_en' => fake()->randomElement(['Citrine', 'Amber', 'Rose quartz', 'Enamel']),
            'color_hex' => '#F2A007',
            'badge' => null,
            'stock' => fake()->numberBetween(1, 50),
            'rating' => fake()->randomFloat(1, 4, 5),
            'review_count' => fake()->numberBetween(0, 200),
            'is_featured' => false,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (): array => ['stock' => 0]);
    }
}
