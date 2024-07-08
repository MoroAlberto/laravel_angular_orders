<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $orders = Order::factory()->count(10)->create();
        $products = Product::factory()->count(30)->create();

        $orders->each(function ($order) use ($products) {
            $randomProducts = $products->random(rand(1, 5));
            $order->products()->attach($randomProducts);
        });

    }
}
