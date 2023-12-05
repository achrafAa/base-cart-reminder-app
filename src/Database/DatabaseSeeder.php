<?php

namespace App\Database;

use App\Models\Cart;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DBCapsule;

class DatabaseSeeder
{
    public function run()
    {
        // Seed the database with products
        $faker = Factory::create();
        $products = [];
        for ($i = 0; $i < 10; $i++) {
            $products[] = [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'image' => $faker->imageUrl(),
                'price' => $faker->randomFloat(2, 10, 100),
            ];
        }
        DBCapsule::table('products')->insert($products);

        $carts = [];
        // Seed the database with carts
        foreach (range(1, 10) as $i) {
            $carts[] = [
                'customer_email' => $faker->email,
                'customer_fullname' => $faker->name,
            ];
        }
        DBCapsule::table('carts')->insert($carts);

        // Seed the database with cart items

        $carts = Cart::inRandomOrder()->get();
        $products = Product::inRandomOrder()->limit(10)->pluck('id')->toArray();
        $cartItems = [];
        foreach ($carts as $cart) {
            foreach (range(1, 5) as $i) {
                $cartItems[] = [
                    'product_id' => $products[rand(0, 9)],
                    'quantity' => $faker->randomDigit(),
                    'price' => $faker->randomFloat(2, 10, 100),
                ];
            }
            $cart->items()->createMany($cartItems);
        }
    }
}
