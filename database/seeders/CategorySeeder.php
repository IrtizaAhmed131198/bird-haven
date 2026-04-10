<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Parrots',      'slug' => 'parrots',      'description' => 'Intelligent and colourful companion parrots.',       'image' => 'cat-parrots.png'],
            ['name' => 'Finches',      'slug' => 'finches',      'description' => 'Small, social and melodic songbirds.',               'image' => 'cat-finches.png'],
            ['name' => 'Canaries',     'slug' => 'canaries',     'description' => 'Celebrated for their beautiful singing voices.',     'image' => 'cat-canaries.png'],
            ['name' => 'Cockatiels',   'slug' => 'cockatiels',   'description' => 'Gentle crested parrots perfect for families.',       'image' => 'cat-cockatiels.png'],
            ['name' => 'Exotic Birds', 'slug' => 'exotic-birds', 'description' => 'Rare and extraordinary species for enthusiasts.',    'image' => 'cat-exotic.png'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
