<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Categories
          $categories = [
            'Electronics',
            'Clothing & Fashion',
            'Home & Garden',
            'Sports & Outdoors',
            'Books & Media',
            'Health & Beauty'
          ];
          foreach ($categories as $category) {
              Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'Description for ' . $category,
                'image' => 'images/default.png', // Assuming a default image path
                'status' => true,
              ]);
          }


          // Seed Products
          Product::create([
              'name' => 'Smartphone',
              'slug' => 'smartphone',
              'description' => 'A high-end smartphone.',
              'unit_price' => 999.99,
              'sale_price' => 899.99,
              'image' => 'images/default.png',
              'status' => true,
              'category_id' => 1,
          ]);
            Product::create([
                'name' => 'T-Shirt',
                'slug' => 't-shirt',
                'description' => 'A comfortable cotton t-shirt.',
                'unit_price' => 19.99,
                'sale_price' => 14.99,
                'image' => 'images/default.png',
                'status' => true,
                'category_id' => 2,
            ]);
            Product::create([
                'name' => 'Garden Chair',
                'slug' => 'garden-chair',
                'description' => 'A stylish garden chair.',
                'unit_price' => 49.99,
                'sale_price' => 39.99,
                'image' => 'images/default.png',
                'status' => true,
                'category_id' => 3,
            ]);
            Product::create([
                'name' => 'Running Shoes',
                'slug' => 'running-shoes',
                'description' => 'Lightweight running shoes.',
                'unit_price' => 79.99,
                'sale_price' => 69.99,
                'image' => 'images/default.png',
                'status' => true,
                'category_id' => 4,
            ]);
            Product::create([
                'name' => 'Cooking Book',
                'slug' => 'cooking-book',
                'description' => 'A book with delicious recipes.',
                'unit_price' => 29.99,
                'sale_price' => 24.99,
                'image' => 'images/default.png',
                'status' => true,
                'category_id' => 5,
            ]);
            Product::create([
                'name' => 'Face Cream',
                'slug' => 'face-cream',
                'description' => 'A moisturizing face cream.',
                'unit_price' => 39.99,
                'sale_price' => 34.99,
                'image' => 'images/default.png',
                'status' => true,
                'category_id' => 6,
            ]);
    }
}
