<?php

namespace Tests\Feature\Models;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;

class OrderProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_orders_and_products_and_associates_them()
    {
        // Run the seeder
        $this->seed(DatabaseSeeder::class);

        // Verify that there are 10 orders
        $this->assertCount(10, Order::all());

        // Verify that there are 30 products
        $this->assertCount(30, Product::all());

        // Verify that each order has between 1 and 5 associated products
        Order::all()->each(function ($order) {
            $this->assertGreaterThanOrEqual(1, $order->products->count());
            $this->assertLessThanOrEqual(5, $order->products->count());
        });
    }

    public function test_it_creates_orders_with_specific_data()
    {
        // Create an order with specific data
        $order = Order::factory()->create([
            'name' => 'Test Order',
            'description' => 'Test Description',
            'date' => now()
        ]);

        // Verify that the order was created correctly in the database
        $this->assertDatabaseHas('orders', [
            'name' => 'Test Order',
            'description' => 'Test Description',
            'date' => $order->date,
        ]);
    }

    public function test_it_creates_products_with_specific_data()
    {
        // Create a product with specific data
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 19.99
        ]);

        // Verify that the product was created correctly in the database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 19.99,
        ]);
    }

    public function test_it_associates_products_to_orders()
    {
        // Create an order
        $order = Order::factory()->create();

        // Create 5 products
        $products = Product::factory()->count(5)->create();

        // Associate the products with the order
        $order->products()->attach($products->pluck('id')->toArray());

        // Verify that the order has 5 associated products
        $this->assertCount(5, $order->products);
    }
}
