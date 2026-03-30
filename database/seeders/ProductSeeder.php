<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Love Handband',
                'price' => 5000,
                'image' => 'assets/images/gift2.jpg',
                'description' => 'Perfect valentine gift',
                'stock' => 10,
            ],
            [
                'name' => 'Pink Bag',
                'price' => 2500,
                'image' => 'assets/images/pinkbag.jpg',
                'description' => 'Stylish and classy',
                'stock' => 15,
            ],
            [
                'name' => 'Hanging Gift',
                'price' => 5000,
                'image' => 'assets/images/hanging.jpg',
                'description' => 'Decorative love item',
                'stock' => 8,
            ],
            [
                'name' => 'Special Gift Box',
                'price' => 8000,
                'image' => 'assets/images/gift2.jpg',
                'description' => 'Premium surprise box',
                'stock' => 12,
            ],
            [
                'name' => 'Clothing',
                'price' => 8000,
                'image' => 'assets/images/tshirt.jpg',
                'description' => 'Clothing item',
                'stock' => 20,
            ],
            [
                'name' => 'Bear',
                'price' => 4000,
                'image' => 'assets/images/bear.png',
                'description' => 'Teddy bear',
                'stock' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
